# Title: Cache-bust filter for Jekyll
# Author: Rex McConnell http://rexmac.com
# Description: Jekyll cachebust filter used for cache-busting of assets (i.e., CSS, JS, and image files)
#
# Syntax:
# {{ 'url_to_asset' | cachebust_url }}
#
# Example:
# {{ '/css/site.css' | cachebust_url }}
#
# Output:
# /css/site.1347843666.css
#
module Jekyll
  module CachebustFilter
    def cachebust_url(input)
      #File.extname(input)
      #"#{input}?#{Time.now.to_i}"
      input.gsub(/^(.+)\.([^.]+)$/, "\\1.#{Time.now.to_i}.\\2")
    end
  end
end

Liquid::Template.register_filter(Jekyll::CachebustFilter)
