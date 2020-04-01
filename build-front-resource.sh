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
current_dirname="$(cd `dirname "${BASH_SOURCE}"`; pwd)"

# 安裝套件
function installModule(){
    local build_dirname="${1}"
    echo "##########################################"
    echo "#### Install Module ........          ####"
    echo "##########################################"
    echo "## PREPARE TO BUILD ${build_dirname} ##"
    cd ${build_dirname}
    if [ ! -d "./node_modules" ]; then
        # 目錄 /path/to/dir 存在
        echo "## NODE BUILD NOT EXIST. ##"
        echo "## PREPARE TO INSTALL........ ##"
        npm install
    else
        # 目錄 /path/to/dir 不存在
        echo "## NODE BUILD EXIST. ##"
    fi
}

# 打包靜態檔
function buildFrontResource(){
    local build_dirname="${current_dirname}/resources/${1}"
    local build_name="${2}"
    installModule "${build_dirname}"
    echo ""
    echo "##########################################"
    echo "#### Build Front Resource ........    ####"
    echo "##########################################"
    npm run build:${build_name}
    cd ${current_dirname}
}

# For 威尼斯人
buildFrontResource "yabo" "yabo"
# For 必博PC
buildFrontResource "yabo" "bibet"
# For 必博手機網頁版(H5)
buildFrontResource "yabo-m" "m6app"
# For 亞博直播(客端直播頁)
buildFrontResource "yabo-m" "yabolive"
# For 亞博直播(後台對應頁)
buildFrontResource "yabo" "yabolive"
# For BB直播 PC
buildFrontResource "yabo" "bbBet"
# For BB直播 手機網頁版
buildFrontResource "yabo-m" "bblivem"

