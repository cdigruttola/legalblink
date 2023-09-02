#!/bin/sh

BASENAME=$(basename "$PWD")

rm -rf tmp
mkdir -p tmp/"$BASENAME"
cp -R docs tmp/"$BASENAME"
cp -R translations tmp/"$BASENAME"
cp -R views tmp/"$BASENAME"
cp -R upgrade tmp/"$BASENAME"
cp -R index.php tmp/"$BASENAME"
cp -R logo.png tmp/"$BASENAME"
cp -R "$BASENAME".php tmp/"$BASENAME"
cp -R config.xml tmp/"$BASENAME"
cp -R LICENSE tmp/"$BASENAME"
cp -R README.md tmp/"$BASENAME"
cd tmp && find . -name ".DS_Store" -delete
zip -r "$BASENAME".zip . -x ".*" -x "__MACOSX"
