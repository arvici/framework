FROM phpdockerio/php73-fpm:latest

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install wget git-core php7.3-mysql php7.3-gd php7.3-intl php7.3-mbstring php7.3-ssh2 php7.3-xdebug php7.3-xsl php7.3-soap \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*


VOLUME [ "/code" ]
WORKDIR "/code"
