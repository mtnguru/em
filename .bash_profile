
ARCH=`uname -m`
export ARCH

HOSTNAME=`hostname`

export CDPATH=.:${HOME}
CDPATH="$CDPATH:${HOME}/drupal/sites/default"

PATH="$PATH:.:${HOME}/bin:${HOME}/bin.sh:${HOME}/bin.pl:/app/bin"

if [ -f $HOME/.bashrc ]; then
   . $HOME/.bashrc
fi

PATH="$PATH:$d/../vendor/drush/drush"

#. xmodmap ~/.xmodmap
#xhost +
set -o vi

export VISUAL=vim
export EDITOR="$VISUAL"

#eval `ssh-agent`
#ssh-add 

echo
fortune
echo

export TZ='America/Denver'

[[ -s "$HOME/.rvm/scripts/rvm" ]] && source "$HOME/.rvm/scripts/rvm" # Load RVM into a shell session *as a function*

# Automatically added by the Platform.sh CLI installer
export PATH='/home/atom/.platformsh/bin':"$PATH"
/home/atom/.platformsh/shell-config.rc 2>/dev/null || true
