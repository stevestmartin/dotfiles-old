namespace :git do
  desc 'Intialize and update submodules'
  task :submodules do
    puts "Initalizing submodules"
    
    `git submodule init`        # init all new submodules
    exec "git submodule update" # update submodules using exec so output gets displayed
  end
end
