# include rake all rake tasks
Dir.glob('lib/tasks/*.rake').each {|rake_file| import rake_file }

# default task setus up environment
task :default => ["git:submodules", "setup:symlink"]
