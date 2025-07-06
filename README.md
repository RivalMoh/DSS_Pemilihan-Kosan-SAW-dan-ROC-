# 🏠 KosanSPK - Smart Boarding House Recommendation System

A comprehensive web-based decision support system that helps students and renters find the perfect boarding house (kos) using advanced recommendation algorithms.

## 🎯 Overview

KosanSPK is a Laravel-powered web application designed to simplify the process of finding suitable boarding houses. The system leverages a powerful combination of multi-criteria decision-making algorithms - **ROC (Rank Order Centroid)** for criteria weighting and **SAW (Simple Additive Weighting)** for scoring - to provide personalized recommendations based on user preferences and criteria.

## ✨ Key Features

### 🔍 Smart Recommendation Engine
- **ROC Algorithm**: Uses Rank Order Centroid method for user preference-based criteria weighting
- **SAW Algorithm**: Implements Simple Additive Weighting for comprehensive scoring
- **SMART Method**: Uses Simple Multi-Attribute Rating Technique for facility scoring
- **Multi-Criteria Evaluation**: Considers 12 key criteria including price, location, facilities, and amenities
- **Personalized Results**: Algorithm-based recommendations with percentage scoring

### 🏘️ Comprehensive Property Management
- **Detailed Listings**: Complete boarding house information with photos and descriptions
- **Multi-Level Facility Tracking**: Room facilities, bathroom facilities, and public facilities
- **Location Intelligence**: Distance-based recommendations from campus locations
- **Room Classifications**: Various room sizes and property types (Putri/Putra/Campur)
- **Photo Gallery**: Multiple photo support for each property
- **Availability Tracking**: Real-time room availability monitoring

### 👥 User Experience
- **Modern Interface**: Clean, responsive design using TailwindCSS and Bootstrap
- **Advanced Filtering**: Filter by budget, facilities, distance, and room preferences
- **Smart Search**: Quick search functionality with multiple filter options
- **Recommendation System**: Algorithm-based suggestions with percentage scores
- **Property Gallery**: Comprehensive photo viewing for each listing
- **User Profiles**: Account management and preference settings

### 🛡️ Admin Dashboard
- **Property Management**: Complete CRUD operations for boarding house listings
- **User Management**: Comprehensive user account administration
- **Weight Configuration**: Adjust recommendation criteria weights and parameters
- **System Analytics**: Monitor recommendation effectiveness and usage
- **Facility Management**: Configure room, bathroom, and public facility options
- **Media Management**: Handle property photos and file uploads

## 🔧 Technology Stack

- **Framework**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Blade Templates, TailwindCSS, Bootstrap 5.2.3, AlpineJS
- **Database**: MySQL/SQLite
- **Build Tools**: Vite
- **Authentication**: Laravel Sanctum & Laravel UI
- **URL Generation**: Eloquent Sluggable
- **Styling**: TailwindCSS with PostCSS
- **Icons**: Bootstrap Icons

## 🚀 Getting Started

### Prerequisites
- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL Database
- XAMPP (recommended for local development)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/kosanspk.git
   cd kosanspk
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kosanspk
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
   
   *Note: The project also supports SQLite for development. A sample SQLite database is included.*

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Build assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## 🏗️ Project Structure

### Key Directories
- **`app/Models/`**: Eloquent models for all entities (Kosan, facilities, criteria, etc.)
- **`app/Services/`**: Business logic services (ROC, SAW, Recommendation algorithms)
- **`app/Http/Controllers/`**: Request handling and route controllers
- **`database/migrations/`**: Database schema definitions
- **`resources/views/`**: Blade template files with TailwindCSS styling
- **`public/storage/`**: File storage for property images

### Key Models
- **`Kosan`**: Main boarding house entity with comprehensive relationships
- **`WeightSetting`**: Configurable criteria weights for recommendations
- **Facility Models**: `FasilitasKamar`, `FasilitasKamarMandi`, `FasilitasUmum`
- **Criteria Models**: `Keamanan`, `Kebersihan`, `Ventilasi`, `Aturan`, etc.

### Services Architecture
- **`KosanRecommendationService`**: Main recommendation engine
- **`RocService`**: ROC algorithm implementation
- **`SawService`**: SAW algorithm implementation
- **`KosanService`**: General kosan business logic

## 🧠 Algorithm Implementation

### ROC (Rank Order Centroid) Algorithm
The ROC algorithm implementation provides user preference-based criteria weighting:
- **User-Driven Ranking**: Allows users to rank criteria by personal importance
- **Centroid Calculation**: Uses mathematical formula: wi = (1/m) × Σ(1/j) for j=i to m
- **Dynamic Weighting**: Adapts weights based on individual user preferences
- **Preference Integration**: Combines user rankings with system recommendations

### SAW (Simple Additive Weighting) Algorithm
The SAW algorithm provides comprehensive scoring with normalization:
- **Multi-Criteria Normalization**: Converts all criteria values to comparable scales (0-1 range)
- **Weighted Scoring**: Applies ROC-derived weights to normalized values
- **Cost/Benefit Handling**: Properly handles both cost criteria (lower is better) and benefit criteria (higher is better)
- **Final Ranking**: Sums weighted scores for comprehensive alternative evaluation

### SMART (Simple Multi-Attribute Rating Technique)
The SMART method enhances facility evaluation:
- **Facility Scoring**: Dedicated scoring system for room, bathroom, and public facilities
- **Order-Based Weighting**: Calculates facility importance based on availability order
- **Comprehensive Integration**: Combines with main criteria for holistic evaluation

## 📊 Evaluation Criteria

The system evaluates boarding houses based on 12 comprehensive criteria:

| Criteria | Type | Weight | Description |
|----------|------|--------|-------------|
| **Price (Harga)** | Cost | 22% | Monthly rental cost |
| **Distance to Campus** | Cost | 17% | Distance from campus location |
| **Room Size** | Benefit | 10% | Room dimensions and space |
| **Security (Keamanan)** | Benefit | 10% | Security measures and safety |
| **Cleanliness (Kebersihan)** | Benefit | 8% | Hygiene and maintenance standards |
| **Location Access** | Benefit | 7% | Access to supporting locations |
| **Additional Fees (Iuran)** | Cost | 4% | Extra charges and fees |
| **Rules (Aturan)** | Cost | 5% | House rules and restrictions |
| **Ventilation** | Benefit | 5% | Air circulation quality |
| **Room Facilities** | Benefit | 5% | In-room amenities |
| **Bathroom Facilities** | Benefit | 3% | Bathroom amenities |
| **Public Facilities** | Benefit | 2% | Common area facilities |

## 🎨 User Interface

### For Renters
- **Home Page**: Browse featured properties with smart recommendations
- **Advanced Search**: Multi-criteria filtering with real-time results
- **Property Details**: Comprehensive information with photo galleries
- **Algorithm-Based Recommendations**: ROC-SAW powered suggestions with scores
- **Responsive Design**: Optimized for desktop and mobile viewing

### For Administrators
- **Admin Dashboard**: System overview with analytics and statistics
- **Property Management**: Full CRUD operations for boarding house listings
- **User Management**: Handle user accounts, roles, and permissions
- **Weight Settings**: Configure and adjust recommendation algorithm parameters
- **Facility Configuration**: Manage room, bathroom, and public facility options
- **Media Management**: Handle property images and file uploads

## 🔄 Current Implementation Status

### ✅ Implemented Features
- ✅ Complete Laravel 10.x application structure
- ✅ ROC (Rank Order Centroid) algorithm implementation
- ✅ SAW (Simple Additive Weighting) algorithm implementation
- ✅ SMART (Simple Multi-Attribute Rating Technique) integration
- ✅ Comprehensive 12-criteria evaluation system
- ✅ Dynamic weight configuration via `WeightSetting` model
- ✅ Multi-level facility management (room, bathroom, public)
- ✅ Property management with photo support
- ✅ User authentication and authorization
- ✅ Admin dashboard for system management
- ✅ Responsive design with TailwindCSS and Bootstrap
- ✅ SQLite database support for development
- ✅ Real-time recommendation scoring

### 🚧 Areas for Enhancement
- User preference setting interface
- Review and rating system
- Advanced search filters UI
- Mobile app optimization
- Performance caching improvements
- API endpoints for external integration

## 🤝 Contributing

We welcome contributions! Please feel free to submit issues, feature requests, or pull requests.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support, please create an issue in the repository or contact the development team.

---

**KosanSPK** - Making boarding house hunting smarter with ROC-SAW-SMART algorithm combination! 🏠✨