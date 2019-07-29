FROM debian:stretch

ENV APP_DIRECTORY /app/
WORKDIR ${APP_DIRECTORY}

RUN apt-get update && apt-get install -y gcc make automake libnet-dev libpcap-dev git procps tcpdump lynx curl strace
RUN git clone https://github.com/Markus-Go/bonesi.git
RUN cd ${APP_DIRECTORY}/bonesi && ./configure && make && make install

COPY entrypoint.sh /
COPY app/* ${APP_DIRECTORY}

VOLUME ${APP_DIRECTORY}

RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]