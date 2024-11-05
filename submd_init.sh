#!/bin/sh
set -e
PWD="$(pwd)/"
git config -f .gitmodules --get-regexp '^submodule\..*\.path$' |
    while read path_key path
    do
        url_key=$(echo $path_key | sed 's/\.path/.url/')
        url=$(git config -f .gitmodules --get "$url_key")
        folder="$PWD$path"

         branch_key=$(echo $path_key | sed 's/\.path/.branch/')
         branch=$(git config -f .gitmodules --get "$branch_key" || true)
         branch_flag=""

         if [ -n "$branch" ]; then
            branch_flag="-b $branch"
         fi

        if [ -d "$folder" ]; then
            echo "-- Folder: $folder exists."
        else
            echo "-- Folder: $folder not exists."
            git submodule add $branch_flag $url $path
        fi
    done
