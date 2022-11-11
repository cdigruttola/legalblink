rm -rf tmp
mkdir -p tmp/legalblink
cp -R classes tmp/legalblink
cp -R config tmp/legalblink
cp -R docs tmp/legalblink
cp -R override tmp/legalblink
cp -R sql tmp/legalblink
cp -R src tmp/legalblink
cp -R translations tmp/legalblink
cp -R views tmp/legalblink
cp -R upgrade tmp/legalblink
cp -R vendor tmp/legalblink
cp -R index.php tmp/legalblink
cp -R logo.png tmp/legalblink
cp -R legalblink.php tmp/legalblink
cp -R config.xml tmp/legalblink
cp -R LICENSE tmp/legalblink
cp -R README.md tmp/legalblink
cd tmp && find . -name ".DS_Store" -delete
zip -r legalblink.zip . -x ".*" -x "__MACOSX"
