# API Документация: Заявки на курсы


## Аутентификация
Все запросы требуют Bearer-токен:
```bash
Authorization: Bearer your-api-token
```

Все эндпоинты доступны по префиксу `/api`.

---

### 1. Получить список курсов
- **GET** `/api/courses`
```bash
- curl -H "Authorization: Bearer test-token" http://localhost:8000/api/courses
```
- Ответ:
```json
[
  {
    "course_id": 1,
    "title": "Веб-разработка на Laravel",
    "description": "Полный курс...",
    "start_date": "2025-03-01",
    "end_date": "2025-06-01"
  }
]
```

---

### 2. Создать заявку
- **POST** `/api/enrollments`
- Ответ:
```json
[
    {
        "course_id": 1,
        "first_name": "Иван",
        "last_name": "Иванов",
        "email": "ivan@example.com"
    }
]
```
- Ошибки:
```
  409 Conflict: если заявка уже есть
  422 Unprocessable Entity: неверные данные
```

---

### 3. Удалить заявку
- **DELETE** `/api/enrollments/{id}`

---

### 4. Получить заявку по email
- **GET** `/api/enrollments/email?email=test@example.com`

---

### 5. Получить всех участников курса
- **GET** `/api/courses/{id}/enrollments`

