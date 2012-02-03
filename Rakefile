task :default => [:setup]

desc 'Symlink all dotfiles'
task :setup => [:create_symlinks] do

end

task :create_symlinks do
  symlinks = {
    '.bashrc' => '.bash_profile'
  }

  puts "Creating symlinks"
  symlinks.each do |dest, src|
    src  = File.expand_path(File.join("~", src))
    dest = File.join(Dir.pwd, dest)

    puts "  #{src} -> #{dest}"
    `cp #{src} #{src}.#{Time.now.strftime("%m%d%Y")}.bak && rm #{src}` if File.exists?(src)
    `ln -s #{dest}  #{src}`
  end
end
