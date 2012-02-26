export PATH=/usr/local/bin:/usr/local/sbin:/usr/local/mysql/bin:$PATH
export CLICOLOR=1
export EDITOR="/usr/local/bin/vim -f"
export VISUAL="/usr/local/bin/vim -f"

# RVM
[[ -s "/Users/stevestmartin/.rvm/scripts/rvm" ]] && source "/Users/stevestmartin/.rvm/scripts/rvm"

# GIT Bash completion
[[ -f "/usr/local/etc/bash_completion.d/git-completion.bash" ]] && source "/usr/local/etc/bash_completion.d/git-completion.bash"

# Git branch
export PS1='[\W$(__git_ps1 " (%s)")]\$ '
