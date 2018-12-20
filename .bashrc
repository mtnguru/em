

# .bashrc - James Sorensen

# Source global definitions
if [ -f /etc/bashrc ]; then
	. /etc/bashrc
fi

# User specific aliases and functions
#alias dp='(du -s * | sort -rn | xpie -title `pwd`) &'
alias ew='vi ~/weight/cabin/2*; unison base -auto'     # edit my weight file

## Colorize the ls output ##
alias ls='ls --color=auto'
 
## Use a long listing format ##
alias ll='ls -la --color=auto'
 
## Show hidden files ##
alias l.='ls -d .* --color=auto'


alias lc='ls -f | wc -l'
alias lt='ls -alt --color | head -20'
alias psg='ps -ef | grep -i'
alias rs='eval `resize -u`; echo $LINES $COLUMNS'
alias fixterm='stty sane;stty erase ^H kill ^U intr ^C eof ^D'
alias dut='du -s * | sort -rn'
alias dus='FILE=/tmp/`basename $PWD`.`date "+%y%m%d"`.du;  \
              echo ; \
	      echo " FILE $FILE " ; \
              echo ; \
              du -s * | sort -rn > $FILE;   \
	      head $FILE'

alias dr='cd /app/web; drush'
alias drc='dr cr'
alias drw='dr watchdog-delete all -y'
alias tc='echo Truncate cache table - database $USER; echo "truncate table cache; quit;" > mysql -u root -ptaichi $USER'

alias fn='find . -name'
alias fm='find . -mtime'

## a quick way to get out of current directory ##
alias ..='cd ../'
alias ...='cd ../../'
alias ....='cd ../../../'
alias .....='cd ../../../../'
alias ......='cd ../../../../../'
 
## Colorize the grep command output for ease of use (good for log files)##
alias grep='grep --color=auto'
alias egrep='egrep --color=auto'
alias fgrep='fgrep --color=auto' 

# install  colordiff package :)
alias diff='colordiff'

alias vi=vim
alias svi='sudo vi'

# Stop after sending count ECHO_REQUEST packets #
alias ping='ping -c 5'
# Do not wait interval 1 second, go fast #
alias fastping='ping -c 100 -s.2'

# reboot / halt / poweroff
alias reboot='sudo /sbin/reboot'
alias poweroff='sudo /sbin/poweroff'
alias halt='sudo /sbin/halt'
alias shutdown='sudo /sbin/shutdown'

## pass options to free ## 
alias meminfo='free -m -l -t'
 
## get top process eating memory
alias psmem='ps auxf | sort -nr -k 5'
alias psmem10='ps auxf | sort -nr -k 5 | head -10'
 
## get top process eating cpu ##
alias pscpu='ps auxf | sort -nr -k 4'
alias pscpu10='ps auxf | sort -nr -k 4 | head -10'
 
## Get server cpu info ##
alias cpuinfo='lscpu'
 
## older system use /proc/cpuinfo ##
##alias cpuinfo='less /proc/cpuinfo' ##
 
## get GPU ram on desktop / laptop## 
alias gpumeminfo='grep -i --color memory /var/log/Xorg.0.log'

## set some other defaults ##
alias df='df -H'
alias dh='du -ch'


## git aliases
alias ga='git add'
alias gs='git status'
alias gd="git diff > /tmp/diff_$LOGNAME; vi /tmp/diff_$LOGNAME"
alias gc='git commit'
alias gr1='git reset HEAD~1'
alias gr2='git reset HEAD~2'
alias gr3='git reset HEAD~3'
alias gk='git checkout'
alias gf='git fetch'
alias gb='git branch'
alias gl='git log'
alias grs='git remote show origin'
alias gbs='git branch --sort=-committerdate'

# cd to $HOME/public_html first
# Don't use these aliases for repositories outside that directory
alias gcw='(echo "cd $d/sites/all - commit work in progress"; cd $d/sites/all; git add .; git commit -m "Work in Progress")'
alias gca='(echo "cd $d - commit all work in progress"; cd $d; git add .; git commit -m "Work in Progress - including settings.php")'

export BLACK=`tput setaf 0 2> /dev/null` #0
export RED=`tput setaf 1 2> /dev/null` #1
export GREEN=`tput setaf 2 2> /dev/null` #2
export YELLOW=`tput setaf 3 2> /dev/null` #3
export BLUE=`tput setaf 4 2> /dev/null` #4
export PURPLE=`tput setaf 5 2> /dev/null` #5
export CYAN=`tput setaf 6 2> /dev/null` #6
export GREY=`tput setaf 7 2> /dev/null` #7
export NORMAL=`tput sgr0 2> /dev/null`
export BOLD=`tput bold 2> /dev/null`

export UCOLOR=$GREY
if [ $USER = "james" ]; then
   UCOLOR=$PURPLE
elif [ $USER = "ed" ]; then
   UCOLOR=$GREEN
elif [ $USER = "ec" ]; then
   UCOLOR=$YELLOW
elif [ $USER = "eb" ]; then
   UCOLOR=$GREEN
elif [ $USER = "atom" ]; then
   UCOLOR=$GREEN
elif [ $USER = "ds" ]; then
   UCOLOR=$PURPLE
elif [ $USER = "eco" ]; then
   UCOLOR=$GREEN
elif [ $USER = "root" ]; then
   UCOLOR=$RED
fi
DCOLOR=$UCOLOR
 
SYSNAME=`hostname`
SCOLOR=$CYAN
export PS1="${UCOLOR}${USER}${SCOLOR}@${SYSNAME}${DCOLOR}:"'${PWD}'"${NORMAL}
> "

export PS2="CONT > "

set -o vi

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"  # This loads nvm

export PATH="$PATH:$HOME/drupal/vendor/console/bin" # Add RVM to PATH for scripting

## Include Drush completion.
if [ -f "$HOME/.drush/drush.complete.sh" ] ; then
  source $HOME/.drush/drush.complete.sh
fi

## Include Drush prompt customizations.
if [ -f "$HOME/.drush/drush.prompt.sh" ] ; then
  source $HOME/.drush/drush.prompt.sh
fi

# Bring in drupal 2 letter aliases
. $HOME/.drupal_aliases

