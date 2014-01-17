# encoding: utf-8
#
# Original: https://github.com/BlackBulletIV/blackbulletiv.github.com/blob/master/_plugins/projects.rb
#
require 'json'
require_relative 'custom_page'
#require_relative 'slugize'

module Jekyll
  class Project < CustomPage
    def initialize(site, base, dir, category, name)
      super site, base, dir, 'project'
      puts "Building project page: #{name}"

      github_name = (category == 'pebble' ? 'pebble-' : '') + name
      releases = JSON.parse(`curl -sS https://api.github.com/repos/#{site.config['author']['github']}/#{github_name}/releases`)
      latest_release = ""
      latest_release_index = 0
      releases.each_with_index do |obj, i|
        unless obj.is_a?(Array) || latest_release > obj['tag_name']
          latest_release = obj['tag_name']
          latest_release_index = i
        end
      end
      return if latest_release === ""
      latest_release = releases[latest_release_index]

      self.data['title'] = name
      self.data['version'] = latest_release['name']
      self.data['repo_url'] = "https://github.com/#{site.config['author']['github']}/#{github_name}"
      self.data['tarball_url'] = "https://codeload.github.com/#{site.config['author']['github']}/#{github_name}/legacy.tar.gz/#{self.data['version']}"
      self.data['zipball_url'] = "https://codeload.github.com/#{site.config['author']['github']}/#{github_name}/legacy.zip/#{self.data['version']}"
      if category == 'pebble'
        self.data['pbw_url'] = "#{self.data['repo_url']}/releases/download/#{self.data['version']}/#{latest_release['assets'][0]['name']}"
      end

      # this stuff is bit hackish, but it works
      readme = `curl -sS https://raw.github.com/#{site.config['author']['github']}/#{github_name}/#{latest_release['name']}/README.md`
      readme.gsub!(/\`{3} ?(\w+)\n(.+?)\n\`{3}/m, "{% highlight \\1 %}\n\\2\n{% endhighlight %}")
      #readme.gsub!(/Ö/, '&#214;')
      readme = Liquid::Template.parse(readme).render({}, :filters => [Jekyll::Filters], :registers => { :site => site })
      self.data['readme'] = Kramdown::Document.new(readme).to_html
    end
  end

  class Projects < CustomPage
    def initialize(site, base, dir, projects)
      super site, base, dir, 'projects'
      data = []
      projects.each { |v| data << v.data }
      self.data['projects'] = data
    end
  end

  class Site
    def generate_projects
      return unless self.config.key? 'projects'
      throw "No 'project' layout found." unless self.layouts.key? 'project'
      dir = self.config['project_dir'] || 'projects'
      project_pages = []

      self.config['projects'].each do |category, projects|
        projects.each do |name|
          #slug = v['slug'] || k.slugize
          slug = name.slugize
          p = Project.new(self, self.source, File.join(dir, category, slug), category, name)
          p.data['url'] = "/#{dir}/#{slug}"
          project_pages << p
          write_page p
        end
      end

      write_page Projects.new(self, self.source, dir, project_pages) if self.layouts.key? 'projects'
    end
  end
end
