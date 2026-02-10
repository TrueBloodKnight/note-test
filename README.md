Запуск: `docker-compose up -d`
Backend доступен на http://localhost:8000, Frontend на http://localhost:3000
Для тестов: `docker exec -it <backend-container> php artisan test`
Swagger: http://localhost:8000/api/documentation

#Endpoints
- GET /api/notes - Все заметки
- GET /api/notes/{id} - Одна заметка
- POST /api/notes - Создать (body: {title, content})
- PUT /api/notes/{id} - Редактировать
- DELETE /api/notes/{id} - Удалить
