require 'redcarpet'

class MyHTML < Redcarpet::Render::HTML
  def link(link, title, content)
    puts "Link: " + link
    puts "Title: " + title
    puts "Content: " + content
  end
end

content = <<eos
http://testlink.org

[mylink](http://mylink.org)

[
eos

content = Redcarpet.new(content).to_html
puts "\n\n" + content
