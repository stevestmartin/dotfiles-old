set nocompatible 			            " Don't run in VI compatability mode (MUST COME FIRST)

" Load pathogen for plugin management
runtime bundle/pathogen/autoload/pathogen.vim
silent! call pathogen#infect()
" silent! call pathogen#helptags()

set showcmd				                " Display incomplete commands.
set showmode				              " Display the mode you're in.

set backspace=indent,eol,start    " Intuitive backspacing.

set hidden				                " Handle multiple buffers better.

set wildmenu				              " Enhanced command line completion.
set wildmode=list:longest		      " Complete files like a shell.

set ignorecase				            " Case-insensitve searching.
set smartcase				              " But case-sensitive if expression contains a capital letter.

set incsearch				              " Highlight matches as you type.
set hlsearch				              " Highlight matches.

set nowrap				                " Turn off line wrapping.
set scrolloff=3				            " Show 3 lines of context around the cursor.

set title				                  " Set the terminal's title.

set visualbell				            " No Beeping.

set nobackup				              " Don't make a backup before overwriting a file.
set nowritebackup			            " And again.
set directory=$HOME/.vim/tmp//,.	" Keep swap files in one location
set noswapfile                    " Turn off swap files

set tabstop=2				              " Global tab width.
set shiftwidth=2			            " and again, related.
set expandtab				              " Use spaces instead of tabs.

set number                        " Display line numbers.

set laststatus=2			            " Show that status line all the time.
set statusline=[%n]\ %<%.99f\ %h%w%m%r%y\ %{fugitive#statusline()}\ %{exists('g:loaded_rvm')?rvm#statusline():''}%{exists('*CapsLockStatusline')?CapsLockStatusline():''}%=%-16(\ %l,%c-%v\ %)%P

syntax enable				              " Turn on syntax highlighting.
set background=dark               " Set background.
colorscheme ir_black              " Color scheme.

filetype plugin indent on		      " Turn on file type detection.

" retag ctags for project and gems
map <silent><Leader>rt :!/usr/local/bin/ctags --extra=+f --exclude=.git --exclude=test --exclude=*.html --exclude=*.haml --exclude=Makefile --exclude=*.min.js --exclude=*.css --exclude=*.sass --exclude=*.yml --exclude=Rakefile --exclude=tmp --exclude=spec --exclude=Gemfile --exclude=Gemfile.lock --exclude=README --exclude=log -R * `bundle show rails`/../*<CR><CR>

" change CtrlP default settings
let g:ctrlp_arg_map = 1
let g:ctrlp_regexp = 1
let g:ctrlp_clear_cache_on_exit = 0
let g:ctrlp_max_height = 15
let g:ctrlp_working_path_mode = 0
let g:ctrlp_extensions = ['tag', 'buffertag', 'quickfix']

map <Leader>f :CtrlP<CR>
map <Leader>b :CtrlPBuffer<CR>
map <Leader>t :CtrlPBufTag<CR>
map <Leader>q :CtrlPQuickfix<CR>

" change zencoding default expansion
let g:user_zen_expandabbr_key = '<c-e>' 
let g:use_zen_complete_tag = 1

au BufRead,BufNewFile *.arb set filetype=ruby
