#!/bin/sh

export NVM_DIR="$HOME/.nvm"
if [[ ! -s "$NVM_DIR/nvm.sh" ]]; then
    echo "## NO NVM, install nvm first ##"
    echo "curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.32.1/install.sh | bash"
    exit
fi

[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion

NODE_VERSION=11.0.0
echo "USING NODE VERSION : "$NODE_VERSION

echo "##########################################"
echo "#### Detecting  NODE VERSION........  ####"
echo "##########################################"
VERSION_EXIST=`nvm list | egrep $NODE_VERSION`
if [[ ! -n "$VERSION_EXIST" ]]; then
   echo "## NODE VERSION NOT EXIST ## "
   echo "## PREPARE TO INSTALL ##"
   nvm install $NODE_VERSION
else
   echo "## NODE VERSION EXIST ## "
   echo "## CHECKOUT VERSION... ##"
   nvm use $NODE_VERSION
fi 
echo ""

echo "##########################################"
echo "#### Detecting  NODE BUILD........    ####"
echo "##########################################"
if [ ! -d "./node_modules" ]; then
    # 目錄 /path/to/dir 存在
    echo "## NODE BUILD NOT EXIST. ##"
    echo "## PREPARE TO INSTALL........ ##"
    npm install
else
    # 目錄 /path/to/dir 不存在
    echo "## NODE BUILD EXIST. ##"
fi

echo ""
echo "#### ALL Checking Done.  ####"

echo "##########################################"
echo "#### Begin to Compile ........        ####"
echo "##########################################"
npm run build:prod

