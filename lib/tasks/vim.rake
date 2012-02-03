namespace :vim do
  desc 'Build command-t vim plugin C extension'
  task :build_command_t do
    puts "Building command-t vim C extension"
    Dir.chdir(File.join(Dir.pwd, ".vim/bundle/command-t/ruby/command-t")) do
      # TODO: check we are compiling against ruby 1.8.7
      `ruby extconf.rb && make`
    end
  end
end
