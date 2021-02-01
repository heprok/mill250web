#!/bin/bash

rm -rf public/build
npm run build

echo
while [ -n "$1" ]
do
case "$1" in
-f) 7z a mill250.7z public config src templates vendor composer.json ;;
*) 7z a mill250.7z public config src templates composer.json ;;
esac
shift
done

mv mill250.7z ~/VirtualBox\ VMs/share