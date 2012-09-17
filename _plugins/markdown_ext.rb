# Extension of Jekyll markdown converter.
#
# Add support for the following:
#   - the contents of nested div elements with markdown="1" attribute are markdownified
#   - replace known abbreviations (defined in _abbreviations.yml) with abbr elements
#   - replace 'gist #n' with link to gist (from https://github.com/BlackBulletIV/blackbulletiv.github.com/blob/master/_plugins/markdown_ext.rb)
#
require 'nokogiri'

module Jekyll
  class MarkdownConverter
    alias :old_convert :convert
    @@abbr_dict = nil

    def convert(content)

      # Process markdown within div blocks that have a markdown="1" attribute
      # The attribute is removed.
      doc = Nokogiri::HTML::fragment(content)
      #doc.xpath("//*[@markdown='1']").each do |el|
      doc.xpath("//div[@markdown='1']").each do |el|
        el.inner_html = RDiscount.new(el.inner_html, *@rdiscount_extensions).to_html
        el.remove_attribute('markdown')
      end
      content = doc.to_s

      # Abbreviations
      # Inspired by https://github.com/kogakure/jekyll-plugin-abbr
      if @@abbr_dict.nil?
        puts "Loading abbreviations..."
        @@abbr_dict = YAML.load(File.open(File.join(@config['source'], "_abbreviations.yml")))
      end
      @@abbr_dict.each do |abbr, title|
        #content.gsub!(/\b#{abbr}\b(?![^">]+">|<\/abbr>|">)/, "<abbr title=\"#{title}\">#{abbr[/[A-Za-z0-9\.\s\;\&]+/]}</abbr>")
        content.gsub!(/\b#{abbr}(s)?\b(?![^">]+">|<\/abbr>|">)/, "<abbr title=\"#{title}\">#{abbr}</abbr>\\1")
      end

      # This adds the gist #1 syntax.
      old_convert content.gsub(/gist #(\d+)/, '[gist #\1](https://gist.github.com/\1)')
    end
  end
end
