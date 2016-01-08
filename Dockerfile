FROM tutum/apache-php
MAINTAINER Genar Trias <genar@acs.li>
ENV uid 1000
ENV gid 1000
RUN usermod -u $uid www-data
RUN groupmod -g $gid www-data
