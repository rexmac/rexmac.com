require 'yaml'
require 'closure-compiler'
require 'colorize'
require 'digest'
require 'fileutils'

ENV['JEKYLL_ENV'] = ENV['JEKYLL_ENV'] ? ENV['JEKYLL_ENV'] : "production"
CONFIG = YAML.load_file("_config.yml")

$dest_dir   = CONFIG['destination'] || '_site'
$src_dir = CONFIG['source'] || '.'
$js_cache_dir = ".js-cache"
rsync_params = "-cvzr --delete #{$dest_dir}/ rexmac@rexmac.com:~/public_html/"

def message(msg, nl = true)
  print msg + (nl ? "\n" : "") unless Rake.verbose == false || Rake.application.options.silent == true
end

def vmessage(msg, nl = true, alt = nil)
  if Rake.verbose == true
    print msg + (nl ? "\n" : "")
  elsif alt != nil
    message alt, nl
  end
end

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
  Rake::Task["css"].invoke
  Rake::Task["js"].invoke
  if !which("jekyll")
    puts "failed. - jekyll executable not found"
  else
    #system "jekyll" + ENV['JEKYLL_ENV'] !== 'development' ? " > /dev/null 2>&1"
    system "jekyll"
  end
  puts "done."
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

desc "Concatenates and minifies JS files according to 'js' setting in _config.yml"
task :js do
  message "Generating javascript...", false
  begin
    # Create js_cache_dir if it doesn't exist
    unless File.directory?($js_cache_dir)
      FileUtils.mkdir_p($js_cache_dir)
    end

    run_closure = false
    processed = Array.new
    # Get configuration
    if CONFIG.key?('js')
      config = CONFIG['js']
      vmessage "Read config: ".colorize(:green) + config.to_s, true
      config.each {|files|
        files.each {|output_file, input_files|
          output_file = File.join($dest_dir, "js", output_file)
          dir = File.dirname(output_file)
          unless File.directory?(dir)
            FileUtils.mkdir_p(dir)
          end

          vmessage "Output file: ".colorize(:cyan) + output_file.to_s
          #vmessage "  Input files: ".colorize(:cyan) + input_files.to_s
          content = ''

          input_files.each {|file|
            file = File.join($src_dir, "js", file)
            processed.push(file)
            vmessage "  Input file: ".colorize(:cyan) + file
            unless File.readable?(file)
              #message "Failed to read file ".colorize(:red) + file
              raise "Failed to read file ".colorize(:red) + file
            end

            content.concat(File.read(file))
          }

          hash = Digest::SHA256.hexdigest(content)
          vmessage "  Hash: ".colorize(:cyan) + hash
          cache_file = File.join($js_cache_dir, hash + ".js")

          # Is there a cached version of the file?
          if File.readable?(cache_file)
            vmessage "  Cached file does exist".colorize(:cyan)
          else
            vmessage "  Cached file does NOT exist".colorize(:cyan)
            # Run content through Google Closure Compiler and cache results
            begin
              content = Closure::Compiler.new.compile(content)
            rescue Closure::Error => e
              message "Failed to minimize JS file, #{output_file}:".colorize(:red)
              message "  " + e.message.gsub(/\n/, "\n  ")
            else
              File.open(cache_file, 'w') do |f|
                f.write(content)
              end
              vmessage "  File cached as ".colorize(:cyan) + cache_file
            end
          end

          # Copy cached version of file to destination directory
          FileUtils.cp(cache_file, output_file)
        }
      }
    else
      raise "No config found!".colorize(:red)
    end

    # Minfiy all JS files with names beginning with an underscore character
    a = Dir.glob(File.join($src_dir, 'js', '**/_*.js')).select {|f|
      processed.index(f).nil?
    }
    a.each {|f|
      content = Closure::Compiler.new.compile(File.read(f))
      File.open(File.join($dest_dir, 'js', File.basename(f, '.js').gsub(/^_/, '') + '.min.js'), 'w') {|f|
        f.write(content)
      }
    }
  rescue RuntimeError => e
    message e.message
  else
    message "done.".colorize(:green)
  end

end

task :deploy do
  if ARGV[1] && ARGV[1] == 'test'
    rsync_params = "-n " + rsync_params
  end

  message "Deploying to remote server...", false
  begin
    if !which("rsync")
      message "failed. - rsync executable not found".colorize(:red)
    elsif rsync_params.nil?
      raise "failed. - no rsync settings found".colorize(:red)
    else
      system "rsync #{rsync_params}"
      message "done".colorize(:green) + '.'
    end
  end

  exit
end
