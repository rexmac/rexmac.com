require 'digest/md5'
require 'less'

module Jekyll

  class LessCssFile < StaticFile
    def write(dest)
      # do nothing
    end
  end

  class LessCss < Liquid::Tag
    @@cache_filename = ''

    def initialize(tag_name, text, tokens)
      super
    end

    def render(context)
      src_dir = context.registers[:site].source
      dest_dir = context.registers[:site].dest
      less_dir = File.join(src_dir, "css", "less")

      # Concatenate content of LESS files
      content = ''
      Dir.glob(File.join(less_dir, "*.less")) {|f|
        content.concat(File.read(f))
      }

      # Hash content to create cached filename
      hash = Digest::MD5.hexdigest(content)
      @@cache_filename = "#{hash}.css"

      # If cached file exists, then use it, otherwise create it
      cache_dir = File.join(src_dir, "_cache")
      FileUtils.mkdir_p(cache_dir)
      cache_file = File.join(cache_dir, @@cache_filename)
      if File.readable?(cache_file)
        content = File.read(cache_file)
      else
        puts "CSS has changed, compiling LESS CSS"
        begin
          parser = Less::Parser.new :paths => [less_dir]
          content = parser.parse(File.read(File.join(less_dir, "site.less"))).to_css(:compress => true)
        rescue Exception => e
          abort("LESS failed: " + e.message)
        end
        File.open(cache_file, 'w') {|f|
          f.write(content)
        }
      end

      # Copy cached CSS file to site destination folder
      css_dir = File.join(dest_dir, "css")
      FileUtils.mkdir_p(css_dir)
      dest_file = File.join(css_dir, @@cache_filename)
      FileUtils.cp(cache_file, dest_file)

      # Add CSS file to static files so it won't be cleaned
      context.registers[:site].static_files.push(
        LessCssFile.new(
          context.registers[:site],
          src_dir,
          File.dirname(dest_file).gsub(dest_dir, ''),
          @@cache_filename
        )
      )

      "<link rel=\"stylesheet\" href=\"/css/#{@@cache_filename}\" />"
    end

  end
end

Liquid::Template.register_tag('cssmincat', Jekyll::LessCss)
