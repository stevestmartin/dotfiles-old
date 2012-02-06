namespace :setup do
  desc 'Create symlinks for all dotfiles'
  task :symlink do
    puts "Creating symlinks"

    symlinks = {
      '.bashrc'    => '.bash_profile',
      '.gitconfig' => '.gitconfig',
      '.gitignore' => '.gitignore',
      '.pryrc'     => '.pryrc',
      '.vim'       => '.vim',
      '.vimrc'     => '.vimrc',
      '.gvimrc'    => '.gvimrc'
    }

    symlinks.each do |dest, src|
      src  = File.expand_path(File.join("~", src))
      dest = File.join(Dir.pwd, dest)

      print "  #{src} -> #{dest}"
      #`cp -R #{src} #{src}.#{Time.now.strftime("%m%d%Y")}.bak && rm -rf #{src}` if File.exists?(src)
      #`ln -s #{dest}  #{src}`
      
      unless `ln -s #{dest} #{src} 2>/dev/null`
        puts " [FAILED]"
      end
    end
  end
end
