# CV Builder

Build and share your resume online. No complicated setup, no emails to confirm. Just write, preview, download as PDF or save multiple versions.

## What can you do here?

- 📝 **Write your resume** — fill out the form with personal info, work experience, education, skills, languages, links
- 📄 **Download as PDF** — get a clean, properly formatted PDF with Cyrillic support 
- 📚 **Save versions** — keep multiple resume drafts in your personal library (SQLite database)
- 🎨 **Dark theme** — easy on the eyes
- ➕ **Add/remove sections** — dynamically add experience, education, skills, languages
- 📱 **Works on mobile** — responsive design
- 🔒 **Your data stays yours** — everything happens locally

## Getting Started Locally

### Requirements
- PHP 8.2+ (8.3 recommended)
- Composer
- Git

### Installation

1. Clone the repo:
```bash
git clone https://github.com/Eeveeu/CV-Builder.git
cd cv-builder
```

2. Install dependencies:
```bash
composer install
```

3. Copy config file:
```bash
cp .env.example .env
```

4. Create necessary directories:
```bash
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p database
chmod -R 755 storage database
```

5. Run the dev server:
```bash
php -S localhost:8000 -t public
```

6. Open in your browser:
```
http://localhost:8000/cv
```

## How to Use

1. **Fill the form** — add your personal info, work experience, education, skills, languages, links
2. **Add sections** — click "➕ Add experience" or "➕ Add education" to add more items
3. **Remove items** — each item has a "❌ Delete" button
4. **Preview** — click "👁️ Preview" to see how it looks
5. **Download** — click "⬇️ Download" to save as PDF (resume.pdf)
6. **Save versions** — click "📚 Save to library" to keep different versions

## Deploy to Production

### Easiest Option: Render.com (Free!)

1. Push your code to GitHub
2. Create a Render account at https://render.com
3. Connect your GitHub repo
4. Deploy in 2 clicks!

Full guide: see `RENDER_DEPLOYMENT.md`

Your app will be live at: `https://your-app.onrender.com/cv`

### Other Options

- **Heroku** — see `DEPLOYMENT.md`
- **VPS (Ubuntu/Debian)** — see `DEPLOYMENT.md`

## API Routes

| Method | Route | What it does |
|--------|-------|-------------|
| GET | `/cv` | Resume form |
| POST | `/cv/store` | Save form data to session |
| GET | `/cv/preview` | Preview your resume |
| GET | `/cv/download` | Download as PDF |
| GET | `/cv/list` | See saved versions |
| GET | `/cv/load/{id}` | Open a saved version |

## Data & Security

- **No sign-up needed** — everything is saved locally in your browser session
- **Save versions permanently** — use "Save to library" to store in SQLite database
- **Data stays private** — we don't send anything to third parties
- **Safe from attacks** — Laravel's built-in CSRF protection, input validation, XSS protection

For more security details: see `SECURITY.md`

## Troubleshooting

### "Page won't load"
- Make sure the server is running: `php -S localhost:8000 -t public`
- Check permissions on `storage` and `database` folders

### "PDF won't download"
- Check that `barryvdh/laravel-dompdf` is installed
- Make sure your browser allows downloads

### "My data disappeared after refreshing"
- That's normal — form data is stored in your session
- Save important resumes to the library so they persist

## Project Structure

```
cv-builder/
├── app/Http/Controllers/
│   └── CVController.php          # Main logic
├── resources/views/cv/
│   ├── index.blade.php           # Resume form
│   ├── preview.blade.php         # Preview/print layout
│   └── print.blade.php           # PDF export layout
├── routes/
│   └── web.php                   # All URL routes
├── database/
│   └── database.sqlite           # Saved resumes (auto-created)
└── composer.json                 # Dependencies
```

## Technology Stack

- **Framework** — Laravel 11 (minimal, lightweight)
- **Language** — PHP 8.3
- **PDF Export** — Dompdf (barryvdh/laravel-dompdf)
- **Database** — SQLite
- **Frontend** — Vanilla JavaScript + CSS
- **Hosting** — Docker + Render.com

## License

MIT License — use freely!

---

Built for people who just want a simple, working resume builder. No ads, no tracking, no nonsense.
