task :default => [:setup]

desc 'Symlink all dotfiles'
task :setup => [:create_symlinks]

task :create_symlinks do
  symlinks = {
    '.bashrc'    => '.bash_profile',
    '.gitconfig' => '.gitconfig',
    '.gitignore' => '.gitignore',
    '.vim'       => '.vim',
    '.vimrc'     => '.vimrc',
    '.gvimrc'    => '.gvimrc'
  }

  puts "Creating symlinks"
  symlinks.each do |dest, src|
    src  = File.expand_path(File.join("~", src))
    dest = File.join(Dir.pwd, dest)

    puts "  #{src} -> #{dest}"
    #`cp -R #{src} #{src}.#{Time.now.strftime("%m%d%Y")}.bak && rm -rf #{src}` if File.exists?(src)
    `ln -s #{dest}  #{src}`
  end
end

task :build_command_t do
  Dir.chdir(File.join(Dir.pwd, ".vim", "bundle", "command-t", "ruby", "command-t")) do
    `ruby extconf.rb && make`
  end
end
