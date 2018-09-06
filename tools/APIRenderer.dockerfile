FROM ubuntu

RUN apt-get update
RUN apt-get install npm -y
RUN npm install -g aglio
