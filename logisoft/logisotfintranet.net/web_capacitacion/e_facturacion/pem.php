<? /* Introducimos el nombre del key provisto por hacienda y su respectivo password, al igual que el nombre del archivo de salida PEM */
shell_exec('openssl pkcs8 -inform DER -in aaa010101aaa_CSD_01.key -passin pass:a0123456789 -out aaa010101aaa_CSD_01.key.pem'); ?>
