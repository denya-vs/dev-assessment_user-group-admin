FROM node:18

WORKDIR /app

COPY ./react-app/package.json ./react-app/package-lock.json ./


COPY ./react-app ./

EXPOSE 3000

CMD ["sh", "-c", "npm install && npm start"]
