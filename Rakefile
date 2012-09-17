require 'yaml'
require 'closure-compiler'

ENV['JEKYLL_ENV'] = ENV['JEKYLL_ENV'] ? ENV['JEKYLL_ENV'] : "production"
CONFIG = YAML.load_file("_config.yml")

def which(cmd)
  exts = ENV['PATHEXT'] ? ENV['PATHEXT'].split(';') : ['']
  ENV['PATH'].split(File::PATH_SEPARATOR).each do |path|
    exts.each { |ext|
      exe = "#{path}/#{cmd}#{ext}"
      return exe if File.executable? exe
    }
  end
  return nil
end

task :default => :build

task :clean do
  print "Cleaning website..."
  system "rm -rf ./_site"
  puts "done."
end

task :build do
  print "Building website..."
  if !which("jekyll")
    puts "failed. - jekyll executable not found"
  else
    #system "jekyll" + ENV['JEKYLL_ENV'] !== 'development' ? " > /dev/null 2>&1"
    system "jekyll"
    puts "done."
  end
end

task :new do
  throw "No title given" unless ARGV[1]
  title = ""
  ARGV[1..ARGV.length - 1].each { |v| title += " #{v}" }
  title.strip!
  now = Time.now
  path = "_posts/#{now.strftime('%F')}-#{title.downcase.gsub(/[\s\.]/, '-').gsub(/[^\w\d\-]/, '')}.md"

  File.open(path, "w") do |f|
    f.puts "---"
    f.puts "layout: post"
    f.puts "title: \"#{title}\""
    f.puts "date: #{now.strftime('%F %T')}"
    f.puts "category: "
    f.puts "tags:"
    f.puts "  - "
    f.puts "---"
    f.puts ""
  end

  `open -a "Sublime Text 2" #{path}`
  exit
end

task :css do
  print "Generating stylesheets..."
  if !which("compass")
    puts "failed. - compass executable not found"
  else
    system "compass clean -q"
    system "compass compile -q"
    puts "done."
  end
end

task :js do
  print "Generating javascript..."
  begin
    # Get list of JS files to be minified and concatenated
    files = []
    if CONFIG.key?('jsmincat')
      files = CONFIG['jsmincat']
    end

    # Concatenate JS files
    content = ''
    files.each {|f|
      content.concat(File.read(f))
    }

    # Minify JS files
    content = Closure::Compiler.new.compile(content)
    File.open('js/site.min.js', 'w') {|f|
      f.write(content)
    }

    # Compile remaining JS files
    a = Dir.glob(File.join('js', '**/_*.js')).select {|f|
      files.index(f).nil?
    }
    a.each {|f|
      content = Closure::Compiler.new.compile(File.read(f))
      File.open(File.join('js', File.basename(f, '.js').gsub(/^_/, '') + '.min.js'), 'w') {|f|
        f.write(content)
      }
    }

    puts "done."
  rescue Closure::Error => e
    puts "failed. - #{e.message}"
  end
end

task :deploy do
  print "Deploying to remote server..."
  if !which("rsync")
    puts "failed. - rsync executable not found"
  #elsif CONFIG['rsync_params'] == "nil"
  elsif CONFIG['rsync_params'].nil?
    puts "failed. - no rsync settings found"
  else
    #system "rsync #{CONFIG['rsync_params']}"
    puts "done."
  end
end

#task :less do
#  lessc_path = '~/.npm/less/1.3.0/package/bin/lessc'
#  less_path = 'css/less/site.less'
#  css_path = '_site/css/style.css'
#  command = [lessc_path,
#             '-x',
#             less_path,
#             css_path
#             ].join(' ')
#
#  #puts 'CSS DIR: ' + File.dirname(css_path)
#  FileUtils.mkdir_p(File.dirname(css_path))
#
#  puts 'Compiling LESS: ' + command
#  `#{command}`
#  raise "LESS compilation error" if $?.to_i != 0
#end
