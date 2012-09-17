require 'digest/md5'
require 'closure-compiler'

module Jekyll

  class JsMinCatFile < StaticFile
    def write(dest)
      # do nothing
    end
  end

  class JsMinCat < Liquid::Tag
    @@cache_filename = ''

    def initialize(tag_name, text, tokens)
      super
    end

    def render(context)
      files = []
      if context.registers[:site].config.key?('jsmincat')
        files = context.registers[:site].config['jsmincat']
      end

      src_dir = context.registers[:site].source
      dest_dir = context.registers[:site].dest

      # Concatenate content of JS files
      content = ''
      files.each {|f|
        content.concat(File.read(File.join(src_dir, f)))
      }

      # Hash content to create cached filename
      hash = Digest::MD5.hexdigest(content)
      @@cache_filename = "#{hash}.js"

      # If cached file exists, use it, otherwise create it
      cache_dir = File.join(src_dir, "_cache")
      FileUtils.mkdir_p(cache_dir)
      cache_file = File.join(cache_dir, @@cache_filename)
      if File.readable?(cache_file)
        content = File.read(cache_file)
      else
        puts "JS has changed, running Google Closure Compiler"
        begin
          content = Closure::Compiler.new.compile(content)
        rescue Closure::Error => e
          abort("Google Closure Compiler failed: " + e.message)
          #$stderr.print "GCC failed: " + $!
          #raise
        end
        f = File.open(cache_file, 'w') {|f|
          f.write(content)
        }
        f.close
      end

      # Copy compiled, cached JS file to site destination folder
      js_dir = File.join(dest_dir, "js")
      FileUtils.mkdir_p(js_dir)
      dest_file = File.join(js_dir, @@cache_filename)
      FileUtils.cp(cache_file, dest_file)

      # Add JS file to static files so it won't be cleaned
      context.registers[:site].static_files.push(
        JsMinCatFile.new(
          context.registers[:site],
          src_dir,
          File.dirname(dest_file).gsub(dest_dir, ''),
          @@cache_filename
        )
      )

      # Remove JS files from static files
      files.each {|f|
        puts "File: #{f}"
        context.registers[:site].static_files.select! {|s|
          if s.class == StaticFile
            s.path != File.join(src_dir, f)
          else
            true
          end
        }
      }

      "<script src=\"/js/#{@@cache_filename}\"></script>"
    end

  end
end

Liquid::Template.register_tag('jsmincat', Jekyll::JsMinCat)
