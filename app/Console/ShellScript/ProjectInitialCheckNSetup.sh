#! /bin/bash


# 檢查專案路徑是否存在
if [ -z $PROJECT_PATH ]; then
    PROJECT_PATH=null
fi
if [ ! -d $PROJECT_PATH ]; then
    echo -------------------------------------------------------
    echo "環境變數:PROJECT_PATH未設定, 或路徑不存在, 請重新設定"
    echo "執行方式：env PROJECT_PATH shell.sh"
    echo -------------------------------------------------------
    exit
fi

# 處理需要建立的目錄
exist_folders=(bootstrap)
for process_folder in ${exist_folders[@]}; do
    real_path=$PROJECT_PATH/$process_folder

    echo Dir Path: $real_path
    if [ ! -d $real_path ]; then
       mkdir $real_path
       echo 1. Folder Check : Folder Not Exist, Create Folder Success!
    else
       echo 1. Folder Check : Folder Exist!
    fi
    echo ---------------------------------------
done

# 處理需要建立以及權限都需要變更為777的目錄
exist_permission_folders=(storage storage/app storage/app/public storage/framework storage/framework/cache storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache)
for process_folder in ${exist_permission_folders[@]}; do
    real_path=$PROJECT_PATH/$process_folder

    echo Dir Path: $real_path
    if [ ! -d $real_path ]; then
       mkdir $real_path
       echo 1. Folder Check : Folder Not Exist, Create Folder Success!
    else
       echo 1. Folder Check : Folder Exist!
    fi

    chmod 777 $real_path -R
    echo 2. Folder Permision Change : Permision Change Success!

    echo ---------------------------------------
done
