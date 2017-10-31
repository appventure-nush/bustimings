FROM ubuntu:16.04

RUN apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0x5a16e7281be7a449
RUN apt-get update -y && apt-get install -y --no-install-recommends software-properties-common \
  && add-apt-repository "deb http://dl.hhvm.com/ubuntu xenial-lts-3.18 main" \
  && apt-get update -y \
  && apt-get install -y --no-install-recommends hhvm \
  && apt-get purge -y software-properties-common \
  && apt-get autoremove -y \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

EXPOSE 8080
WORKDIR /srv

COPY 4.php 5.php index.php config.php /srv/

ENTRYPOINT ["/usr/bin/hhvm", "-m", "s", "-v", "Server.AllowRunAsRoot=1", "-p", "8080"]
