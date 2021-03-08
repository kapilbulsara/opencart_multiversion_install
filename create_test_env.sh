#! /bin/bash

source ./config

tags=`git ls-remote --tags "$repo" | cut -f2 | cut -f3 -d /`


if [ ! -d "$dest" ]
then 
		mkdir -p "$dest"
fi
cd "$dest"
for tag in $tags
do 
		git clone "$repo" --branch="$tag"  "$tag" 
		cd "$tag"
		git checkout -b master
		cd ..
done

