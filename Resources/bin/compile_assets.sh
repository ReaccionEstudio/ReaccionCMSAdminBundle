# PATHS
__DIR__="`dirname \"$0\"`"
SF_PATH=${__DIR__}/../../../../../
REACCION_ADMIN_BUNDLES_TEMPLATES_PATH=${SF_PATH}/src/ReaccionEstudio/ReaccionCMSAdminBundle/Resources/views/bundles
BUNDLES_TEMPLATES_PATH=${SF_PATH}/templates

# OVERRIDE THIRD-PARTY BUNDLE VIEWS
echo "Removing ${BUNDLES_TEMPLATES_PATH}/bundles folder ..."

rm -R ${BUNDLES_TEMPLATES_PATH}/bundles

# echo "Copying ${REACCION_ADMIN_BUNDLES_TEMPLATES_PATH} in ${BUNDLES_TEMPLATES_PATH}/bundles ..."
cp -R ${REACCION_ADMIN_BUNDLES_TEMPLATES_PATH} ${BUNDLES_TEMPLATES_PATH}/bundles

# install assets
php bin/console assets:install --symlink 

# Run Webpack encore
./node_modules/.bin/encore dev # TODO: add request param for selecting an environment