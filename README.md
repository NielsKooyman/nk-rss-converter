# nk-rss-converter

This repository provides custom RSS feeds for amusement parks that do not offer their own. The script is self-hosted and integrated into our **MonitoRSS** stack (Docker). It enables us to deliver news, blog posts, and press articles directly to the **Niels Kooyman [Discord-server](https://discord.nielskooyman.io/)**, keeping our members informed with the latest updates.

## 🎢 Supported Parks
Currently, this project generates RSS feeds for:
- **Toverland** (toverblog.php)
- **Walibi Holland** (press.php)
- **Walibi Belgium** (press.php)

## 🛠 How It Works
Each PHP script scrapes the respective park's website for news updates and converts the content into an RSS feed. This allows MonitoRSS or any other RSS reader to retrieve the latest news.

## 📁 File Structure
```
├── README.md           # This file
├── LICENSE             # MIT License
├── toverland/          # RSS scraper for Toverland
│   ├── toverblog.php
├── walibi-holland/     # RSS scraper for Walibi Holland
│   ├── press.php
├── walibi-belgium/     # RSS scraper for Walibi Belgium
│   ├── press.php
```

## 🚀 Usage
1. Clone the repository:
   ```sh
   git clone https://github.com/NielsKooyman/nk-rss-converter.git
   ```

2. Set up a PHP-enabled environment (e.g., using Docker or a local PHP server).

3. Run the scripts to generate RSS feeds:
   ```sh
   php toverland/toverblog.php
   php walibi-holland/press.php
   php walibi-belgium/press.php
   ```

4. Use the generated RSS feed URLs in MonitoRSS or any RSS reader.

## 📜 License
This project is licensed under the **MIT License**, allowing free use, modification, and distribution as long as the original license is retained.

---

For contributions or issues, feel free to open a pull request or submit an issue! ✨

