# 🧠 NeuroMatch

NeuroMatch adalah platform social matching berbasis **EEG (gelombang otak)** yang menghubungkan orang-orang berdasarkan mood & personality mereka—langsung dari data otak lo sendiri!

## 🚀 Fitur Utama
- **Register & Login** (API Laravel + SPA Vue 3)
- **Upload EEG (CSV)**: Data otak lo diolah, dikirim ke ML, dan hasil mood/personality langsung muncul di profile
- **Profile**: Liat mood & personality hasil analisis ML
- **Match**: Temuin user lain yang cocok secara kepribadian/mood
- **Auth & Security**: Laravel Sanctum, token-based, route guard frontend

## 🛠️ Stack
- **Backend**: Laravel 11 (API, Auth, EEG upload, integrasi ML)
- **Frontend**: Vue 3 + Vite + TypeScript + Pinia + TailwindCSS
- **ML Service**: Python Flask (dummy model, bisa diupgrade ke real ML)
- **Database**: MySQL/PostgreSQL (bisa pilih)

## ⚡ Cara Jalanin (Dev)
1. **Clone repo & install dep**
   ```bash
   git clone <repo-url>
   cd neuromatch
   # Backend
   cd backend
   composer install
   cp .env.example .env
   php artisan key:generate
   # (atur DB di .env, lalu)
   php artisan migrate --seed
   php artisan serve
   # Frontend
   cd ../frontend
   npm install
   npm run dev
   # ML Service
   cd ../ml_service
   pip install -r requirements.txt
   python app.py
   ```
2. **Akses**
   - Frontend: [http://localhost:5173](http://localhost:5173)
   - Backend API: [http://localhost:8000/api](http://localhost:8000/api)
   - ML Service: [http://localhost:5000](http://localhost:5000)

## 📁 Format EEG CSV
```
alpha,beta,gamma,theta,delta
8.5,12.3,25.7,4.2,2.1
...
```

## ✨ Keunikan
- **Social matching pake data otak beneran!**
- Fullstack modern, clean, dan scalable
- Bisa diintegrasi ke ML model beneran (tinggal ganti service Flask-nya)
- UI/UX sat set, mobile friendly

---

> Dibuat dengan ❤️ oleh Gen Z, untuk masa depan social platform yang lebih personal & ilmiah.
