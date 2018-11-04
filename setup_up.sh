#!/bin/bash
real_path=$(cd $(dirname $0) && pwd)
#cd ${real_path}

if command -v php >/dev/null 2>&1; then
  echo 'php cli Check Complete!'
   php_script='php';
   env_lv="$1";
else
  echo 'no exists git'
  php_script="$1";
  env_lv="$2";
fi
#php_script="$1";

phpversion=$($php_script --version | tail -r | tail -n 1 | cut -d " " -f 2 | cut -c 1,3);
echo 'PHP Version: '$phpversion;
#-ge
if [[ $phpversion -ge 70 ]];then
echo "PHP Version > 70 Check Ok;"
else
echo "PHP Version > 70 Check Fail;";
echo 'Exit.';
exit
fi

#$php_script composer.phar update

env_file="${real_path}/.env";
if [ -f $env_file ]
then
	echo ".env has been created."
else
    if [ "$env_lv" = "dev" ] ; then
        cp "${real_path}/.env_dev" $env_file;
    fi
    if [ "$env_lv" = "prod" ] ; then
        cp "${real_path}/.env_prod" $env_file;
    fi

    echo '.env created Success.'
fi

vendor_file="${real_path}/vendor.tar.gz";
vendor_dir="${real_path}/vendor";

if [ -f $vendor_file ]; then
    if [ ! -d "$vendor_dir" ]; then
        echo "vendor file start tar.";
        tar -zxvf "$vendor_file" ;
    else
        echo "vendor file has been created.";
    fi
else
    echo 'vendor file Fail';
fi

$php_script ${real_path}/artisan env
$php_script ${real_path}/artisan migrate
$php_script ${real_path}/artisan admin:install
$php_script ${real_path}/artisan admin:import helpers
$php_script ${real_path}/artisan vendor:publish --tag=api-tester
$php_script ${real_path}/artisan admin:import api-tester
$php_script ${real_path}/artisan admin:import config
$php_script ${real_path}/artisan admin:import scheduling
#$php_script ${real_path}/artisan env

echo 'Completed.'
