FROM node:18

WORKDIR /app

COPY ./react-app/package.json ./react-app/package-lock.json ./

RUN npm install

COPY ./react-app ./

EXPOSE 3000

CMD ["npm", "start"]
