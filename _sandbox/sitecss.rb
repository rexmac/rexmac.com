# Heavily inspired by jekyll-sass (http://github.com/noct/jekyll-sass)
require 'digest/md5'
require 'sass'

module Jekyll

  class SiteCssFile < StaticFile
    def write(dest)
      # no-op
    end
  end

  class SiteCss < Liquid::Tag
    @@cache_filename = ''

    def initialize(tag_name, text, tokens)
      super
    end

    def render(context)
      src_dir = context.registers[:site].source
      dest_dir = context.registers[:site].dest
      sass_dir = File.join(src_dir, "assets", "sass")
      #puts "sass_dir = #{sass_dir}"

      # Concatenate content of SASS files
      content = ''
      Dir.glob(File.join(sass_dir, "**/*.scss")) {|f|
        #puts "Cat file #{f}"
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
        puts "Site CSS has changed, compiling SASS CSS"
        begin
          #engine = ::Sass::Engine.new(File.read(File.join(sass_dir, "site.scss")), :syntax => :scss, :load_paths => [sass_dir], :style => :compressed)
          #engine = ::Sass::Engine.new(File.read(File.join(sass_dir, "site.scss")), :syntax => :scss, :load_paths => [sass_dir])
          #content = engine.render
          content = File.read(File.join(sass_dir, "site.scss"))
        rescue => e
          abort("SASS generation for site CSS failed: #{e.message}")
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
        SiteCssFile.new(
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

Liquid::Template.register_tag('sitecss', Jekyll::SiteCss)
