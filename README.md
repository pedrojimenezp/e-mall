E-Mall

E-Mall es un sitio web que le permite a un usuario crear una tienda online de manera rapida y segura.


===============================================================================================================

Acontinuacion estan los pasos para poner a funcionar E-Mall en nuestro servidor local.
Cabe recordar que estos paso se siguieron y se probaron en una maquina GNU/Linux con ubuntu 12.10 y funcionaron perfectamente.

Si tiene alguna otra distro GNU/Linux los archivos pueden estar en otras rutas, pero la configuracion es la misma.


Para corre E-Mall en su servidor Apache en Ubuntu 12.10 debe tener los siguientes requisitos:
    
    1)  Debe tener un servidor LAMP perfectamente instalado
        Si no lo tiene instalelo.

    2)  Debe tener habilitado el modulo rewrite en su servidor apache.
        Si no lo tiente debe abilitarlo:
            
            sudo a2enmon mod-rewrite
    
    3)  Debe tener PHP 5.4 o superior instalado

    4)  Debe tener MySql instalado

    5)  Es aconsejable pero no un requisito tener git instalado en el sistema 

Despues de cumplir con todos los requisitos de arriba siga los siguientes pasos:
    
    1)  Entrar en el directorio raiz del servidor, por defecto es /var/www/

        cd /var/www/
        
    2)  Copiar E-Mall dentro de /var/www/
        
        a)  Si no tiene git instalado en su sistema entonces descargue E-Mall de github y descomprimala dentro de /var/www/

        b) Si efectivamente si tiene git instalado en su sistema entonces ejecute la siguiente linea en consola (debe estar dentro de /var/www/)
            
            git clone git://github.com/pedrojimenezp/E-Mall.git 

            La line anterior descargara todo el codigo de E-Mall a su sistema dentro del directorio donde se encuentra en este caso /var/www.
   
    3)  Crear un archivo llamado E-Mall dentro de /etc/apache2/sites-avalible
        
        a) cd /etc/apache2/sites-avalible
        
        b) sudo gedit E-Mall

            dentro de ese archivo pegar lo siguiente:

            <VirtualHost *:80>
                serverName E-Mall
                serverAlias E-Mall.com
                ServerAdmin tucuenta@host.com

                DocumentRoot /var/www/E-Mall

                <Directory />
                        Options FollowSymLinks
                        AllowOverride all
                </Directory>

                <Directory /var/www/E-Mall>
                        Options Indexes FollowSymLinks MultiViews
                        AllowOverride all
                        Order allow,deny
                        allow from all
                </Directory>

                ErrorLog ${APACHE_LOG_DIR}/error.log

                # Possible values include: debug, info, notice, warn, error, crit,
                # alert, emerg.
                LogLevel warn

                CustomLog ${APACHE_LOG_DIR}/access.log combined
            </VirtualHost>


        c)  Guardar los cambios -> ctrl + s

    4)  Agregar el nombre del host a los host de apache:

        a)  sudo gedit /etc/host

        b)  agregar la siguiente linea:
            127.0.0.1   E-Mall.com

        c) guardar los cambios -> ctrl + s

    5)  Habilita el sitio:

        sudo a2ensite E-Mall

    6) Reiniciar el servidor:

        sudo service apache2 reload
