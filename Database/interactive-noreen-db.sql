-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2026 at 08:11 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `interactive-noreen-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `agent_id` int(11) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `expertise_domain` varchar(200) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`agent_id`, `full_name`, `email`, `password`, `phone`, `expertise_domain`, `bio`, `experience_years`, `approval_status`, `created_at`) VALUES
(1, 'Agent one', 'agent@gmail.com', '$2y$10$7bhtsYmZOIeB2QxtURQoZ.XNsAWWY4pj2sZ2424ODnCtTPxfAFvwK', '03176526827', 'Marketing', 'XYZ', 23, 'approved', '2026-03-07 05:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `business_ideas`
--

CREATE TABLE `business_ideas` (
  `idea_id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `investment_required` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_ideas`
--

INSERT INTO `business_ideas` (`idea_id`, `agent_id`, `category_id`, `title`, `description`, `investment_required`, `created_at`) VALUES
(2, 1, 2, 'Cloud Kitchen for Desi Fast Food', 'Set up a delivery-only kitchen specializing in affordable biryani, karahi, burgers, and rolls for food delivery apps like Foodpanda & Careem.', 1200000.00, '2026-03-07 06:25:52'),
(3, 1, 3, 'Home-Based Herbal Wellness Products', 'Produce and sell natural herbal teas, immunity boosters, and skincare items (honey-ginger, moringa, neem) via Instagram and Daraz.', 450000.00, '2026-03-07 06:25:52'),
(4, 1, 4, 'Online Quran & Arabic Learning Platform', 'Offer live one-on-one and group classes for Quran recitation, tajweed, and basic Arabic for kids & adults — fully online.', 380000.00, '2026-03-07 06:25:52'),
(5, 1, 5, 'Custom POS & Inventory Software for Small Shops', 'Develop affordable, mobile-friendly POS software tailored for Pakistani retail stores (grocery, pharmacy, clothing).', 1800000.00, '2026-03-07 06:25:52'),
(6, 1, 6, 'Local Ride-Sharing App for University Students', 'Niche ride-sharing service connecting students within university areas and nearby hostels with verified drivers.', 2500000.00, '2026-03-07 06:25:52'),
(7, 1, 7, 'Affordable Modest Fashion Brand', 'Launch a clothing line of hijabs, abayas, and kurtas with modern designs at budget prices — sell online + pop-up stores.', 950000.00, '2026-03-07 06:25:52'),
(8, 1, 8, 'Vertical Farming of Microgreens & Herbs', 'Grow high-value microgreens, basil, mint in small urban space using hydroponics — supply to cafes & restaurants.', 1400000.00, '2026-03-07 06:25:52'),
(9, 1, 9, 'Low-Cost Property Management Service', 'Help overseas Pakistanis manage rental properties — tenant screening, rent collection, maintenance coordination.', 320000.00, '2026-03-07 06:25:52'),
(10, 1, 10, 'Budget Domestic & Umrah Travel Packages', 'Organize affordable group travel packages for domestic tourism and Umrah with transparent pricing.', 1100000.00, '2026-03-07 06:25:52'),
(11, 1, 11, 'Solar-Powered Mobile Charging Stations', 'Install solar-powered charging kiosks at markets, bus stops, and rural areas — charge phones & small devices.', 850000.00, '2026-03-07 06:25:52'),
(12, 1, 12, 'Micro-Loan Facilitation for Women Entrepreneurs', 'Act as agent connecting women with microfinance banks & provide training on loan application & business basics.', 280000.00, '2026-03-07 06:25:52'),
(13, 1, 13, 'Same-Day Courier Service for Small Towns', 'Last-mile delivery service focusing on towns & villages using motorbikes — partner with Daraz & local e-commerce.', 680000.00, '2026-03-07 06:25:52'),
(14, 1, 14, 'Men’s Grooming & Beard Care Products', 'Create and sell natural beard oils, balms, washes using local ingredients — target young men via social media.', 520000.00, '2026-03-07 06:25:52'),
(15, 1, 15, 'TikTok & Instagram Content Creation Agency', 'Help small businesses create short-form video content, reels, and ads — monthly packages starting low.', 420000.00, '2026-03-07 06:25:52'),
(16, 1, 16, 'Custom T-Shirt & Mug Printing Unit', 'Small-scale DTG printing setup for personalized gifts, corporate uniforms, school events.', 1600000.00, '2026-03-07 06:25:52'),
(17, 1, 17, 'Freelancer Skill-Building Bootcamp', 'Run weekend physical/online bootcamps teaching freelancing skills (graphic design, copywriting, video editing).', 550000.00, '2026-03-07 06:25:52'),
(18, 1, 18, 'YouTube Channel Automation Service', 'Help creators outsource scripting, editing, thumbnail design, and SEO for faceless YouTube channels.', 380000.00, '2026-03-07 06:25:52'),
(19, 1, 19, 'Used Japanese Car Import Consultancy', 'Guide clients through the process of importing auction cars from Japan — documentation, shipping, customs clearance.', 650000.00, '2026-03-07 06:25:52'),
(20, 1, 20, 'Wedding & Event Planning on Budget', 'Affordable wedding services focusing on middle-class families — venue decoration, catering coordination, photography.', 780000.00, '2026-03-07 06:25:52'),
(21, 1, 21, 'Corporate & Birthday Event Management', 'Specialize in office events, team-building days, and children’s birthday parties with unique themes.', 620000.00, '2026-03-07 06:25:52');

-- --------------------------------------------------------

--
-- Table structure for table `business_templates`
--

CREATE TABLE `business_templates` (
  `template_id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_templates`
--

INSERT INTO `business_templates` (`template_id`, `agent_id`, `title`, `description`, `file_path`, `uploaded_at`) VALUES
(23, 1, 'Startup Business Plan Template', 'Comprehensive fillable template covering executive summary, company description, market analysis, marketing plan, operations, management, and financial projections — ideal for new entrepreneurs.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(24, 1, 'One-Page Business Plan Template', 'Concise single-page overview format to quickly outline your business idea, target market, value proposition, revenue streams, and key metrics.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(25, 1, 'Lean Canvas Template', 'Adapted from Business Model Canvas — focused on problem, solution, key metrics, unfair advantage, channels, customer segments, cost structure, and revenue streams for startups.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(26, 1, 'Investor Pitch Deck Template', '10-15 slide structure including problem, solution, market size, product demo, traction, team, financials, and ask — ready for PowerPoint/Google Slides export.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(27, 1, 'Financial Projections Template (3-5 Years)', 'Excel-style monthly/annual forecast template with income statement, cash flow, balance sheet, break-even analysis, and startup costs breakdown.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(28, 1, 'SWOT Analysis Template', 'Structured grid to assess Strengths, Weaknesses, Opportunities, and Threats for your business idea or existing venture.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(29, 1, 'Marketing Plan Template', 'Step-by-step guide including target audience, competitive analysis, marketing mix (4Ps), budget allocation, and timeline for campaigns.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(30, 1, 'Competitor Analysis Template', 'Comparison table format to evaluate direct/indirect competitors on pricing, features, strengths/weaknesses, and market positioning.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(31, 1, 'Customer Persona Template', 'Detailed profile builder for ideal customer — demographics, behaviors, pain points, goals, and how your product solves them.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(32, 1, 'Pricing Strategy Worksheet', 'Template to calculate cost-plus, value-based, competitor-based pricing with profit margin calculator and sensitivity analysis.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(33, 1, 'Cash Flow Forecast Template', 'Monthly cash inflow/outflow tracker with sections for operating, investing, financing activities — critical for bootstrapped startups.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(34, 1, 'Startup Expenses Checklist', 'Categorized list of one-time and recurring costs (legal, equipment, marketing, inventory) with estimated budget fields.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(35, 1, 'Partnership Agreement Outline', 'Basic template for joint ventures or collaborations — covering roles, profit sharing, responsibilities, duration, and exit clauses.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(36, 1, 'Invoice Template for Freelancers/Small Biz', 'Professional, customizable invoice format with your logo, tax details, payment terms, and line items.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(37, 1, 'Break-Even Analysis Calculator', 'Simple tool to determine how many units/services you need to sell to cover costs — includes graph visualization tips.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(38, 1, 'Social Media Content Calendar Template', 'Monthly planner grid for posting schedule, content type (reel, post, story), captions, hashtags, and performance tracking.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(39, 1, 'OKR Goal Setting Template', 'Objectives and Key Results framework for quarterly planning — popular with startups for measurable growth tracking.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(40, 1, 'Risk Assessment & Mitigation Plan', 'Table format to identify business risks (financial, operational, market), probability, impact, and contingency actions.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(41, 1, 'Elevator Pitch Script Template', '30-second to 2-minute pitch structure — problem, solution, market opportunity, traction, team, and call-to-action.', '1772864932_export.pdf', '2026-03-07 06:32:27'),
(42, 1, 'Exit Strategy Planning Template', 'Outline for potential business exits (sale, acquisition, succession) including valuation methods and timeline preparation.', '1772864932_export.pdf', '2026-03-07 06:32:27');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(2, 'E-commerce & Online Retail'),
(3, 'Food & Beverage'),
(4, 'Health & Wellness'),
(5, 'Education & EdTech'),
(6, 'Information Technology & Software'),
(7, 'Mobile Applications & Tech Services'),
(8, 'Fashion & Apparel'),
(9, 'Agriculture & Agribusiness'),
(10, 'Real Estate & Property Services'),
(11, 'Tourism & Hospitality'),
(12, 'Renewable Energy & Sustainability'),
(13, 'Financial Services & FinTech'),
(14, 'Logistics & Supply Chain'),
(15, 'Beauty & Personal Care'),
(16, 'Digital Marketing & Advertising'),
(17, 'Manufacturing & Small-scale Production'),
(18, 'Consulting Services'),
(19, 'Creative Industries (Design, Arts, Media)'),
(20, 'Automotive & Transportation'),
(21, 'Event Management & Planning');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `description`, `event_date`, `location`) VALUES
(2, 'Startup Weekend Lahore 2026', '54-hour intensive where participants pitch ideas, form teams, build prototypes, and present to judges and investors.', '2026-04-10', 'Lahore - LUMS Campus'),
(3, 'World Startup Championship Pakistan Regional Round - Karachi', 'National-level pitch competition with mentorship, investor meetings, and chance to advance to global finals.', '2026-05-15', 'Karachi - Bahria University'),
(4, 'Global Entrepreneurship Week Pakistan 2026', 'Week-long celebration with workshops, panels, idea expos, and networking sessions across major cities.', '2026-06-08', 'Multiple Cities (Karachi, Lahore, Islamabad)'),
(5, 'Women Entrepreneurs Summit 2026', 'Empowering female founders with sessions on funding, scaling, leadership, and success stories from Pakistan.', '2026-07-12', 'Lahore - Expo Centre'),
(6, 'Investor-Founder Matching Meetup Karachi', 'Speed-networking event connecting early-stage startups with angel investors and VCs.', '2026-03-25', 'Karachi - Online & In-person hybrid'),
(7, 'Digital Marketing Masterclass for Startups', 'Hands-on workshop on SEO, social media ads, content strategy, and low-budget growth hacks.', '2026-04-05', 'Islamabad - Arfa Software Technology Park'),
(8, 'Pitch Deck Workshop & Review Session', 'Learn to create investor-ready pitch decks + live feedback from experienced mentors.', '2026-05-20', 'Online (Zoom)'),
(9, 'Made in Pakistan MSME Expo 2026', 'Large-scale exhibition for small & medium enterprises, product showcases, B2B meetings, and government schemes.', '2026-01-17', 'Lahore Expo Centre'),
(10, 'Enterprise Challenge Pakistan Finals 2026', 'National student entrepreneurship competition showcasing innovative business ideas from schools/colleges.', '2026-06-22', 'Islamabad - National Level Venue'),
(11, 'AI for Entrepreneurs Webinar Series', 'Explore how AI tools can help startups with automation, customer service, content creation, and more.', '2026-04-18', 'Online Webinar'),
(12, 'Bootstrapping Your Startup Successfully', 'Real founder stories and strategies to grow without external funding in the Pakistani market.', '2026-05-08', 'Lahore - NIC Lahore'),
(13, 'E-commerce Growth Hacks 2026', 'Practical session on scaling online stores using Daraz, Shopify, social commerce, and logistics tips.', '2026-07-05', 'Karachi - Online'),
(14, 'Business Idea Competition 2026 - Rawalpindi', 'Pitch your raw business idea for cash prizes, incubation support, and mentorship.', '2026-08-10', 'Rawalpindi Chamber of Commerce'),
(15, 'FinTech & Digital Payments Workshop', 'Deep dive into building payment solutions, regulatory compliance, and opportunities in Pakistan’s fintech space.', '2026-09-14', 'Islamabad - Ignite National Technology Fund'),
(16, 'Sustainable Business Models for Pakistan', 'Workshop on green startups, circular economy, solar energy businesses, and eco-friendly opportunities.', '2026-10-20', 'Lahore'),
(17, 'ScaleUp Pakistan Multi-City Roadshow', 'Entrepreneur conference series hitting 12 cities — networking, masterclasses, and growth strategies.', '2026-11-05', 'Karachi (kickoff)'),
(18, 'Startup Grind Pakistan Monthly Meetup', 'Inspiring talks by local founders, investor panels, and high-energy networking.', '2026-04-28', 'Islamabad'),
(19, 'Legal & Tax Essentials for New Businesses', 'Session covering company registration, NTN, sales tax, contracts, and avoiding common pitfalls in Pakistan.', '2026-05-25', 'Online'),
(20, 'Youth Entrepreneurship Bootcamp 2026', '3-day intensive training on ideation, MVP building, marketing, and pitching for young aspiring entrepreneurs.', '2026-06-15', 'Karachi - Habib University'),
(21, 'Pakistan Startup Ecosystem Report Launch 2026', 'Event unveiling latest trends, funding data, success stories, and policy recommendations.', '2026-07-25', 'Lahore - Pearl Continental');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `agent_id`, `rating`, `comment`, `created_at`) VALUES
(1, 3, 1, 2, 'fds', '2026-03-07 07:03:14');

-- --------------------------------------------------------

--
-- Table structure for table `forum_replies`
--

CREATE TABLE `forum_replies` (
  `reply_id` int(11) NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_replies`
--

INSERT INTO `forum_replies` (`reply_id`, `topic_id`, `user_id`, `message`, `created_at`) VALUES
(1, 1, 3, 'fas', '2026-03-07 07:07:15');

-- --------------------------------------------------------

--
-- Table structure for table `forum_topics`
--

CREATE TABLE `forum_topics` (
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_topics`
--

INSERT INTO `forum_topics` (`topic_id`, `user_id`, `title`, `description`, `created_at`) VALUES
(1, 3, 'fdsa', 'fa', '2026-03-07 07:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `mentorship_requests`
--

CREATE TABLE `mentorship_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mentorship_requests`
--

INSERT INTO `mentorship_requests` (`request_id`, `user_id`, `agent_id`, `message`, `status`, `request_date`) VALUES
(1, 3, 1, 'Sir How are you', 'accepted', '2026-03-07 06:57:51');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `sent_at`) VALUES
(1, 3, 1, 'Hy Sir', '2026-03-07 06:57:58'),
(2, 1, 3, 'Yes', '2026-03-07 06:59:51');

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

CREATE TABLE `opportunities` (
  `opportunity_id` int(11) NOT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opportunities`
--

INSERT INTO `opportunities` (`opportunity_id`, `agent_id`, `title`, `description`, `location`, `created_at`) VALUES
(2, 1, 'E-commerce Niche Store Launch Opportunity', 'Partner or start a specialized online store (modest fashion, organic products, gadgets) on Daraz/Shopify. Market projected to hit $14B+ with 18-22% annual growth.', 'Pakistan-wide (Online)', '2026-03-07 06:37:31'),
(3, 1, 'AI-Powered Services Agency Setup', 'Build an agency offering AI chatbots, content generation, or automation tools for local businesses. Sector expected to grow from $949M to $3.23B by 2030 with govt AI policy support.', 'Karachi / Lahore', '2026-03-07 06:37:31'),
(4, 1, 'AgriTech Solutions for Farmers', 'Develop or invest in apps/tools for crop monitoring, supply chain, or vertical farming. Massive 5-7 trillion PKR market potential with low current funding.', 'Punjab Rural Areas', '2026-03-07 06:37:31'),
(5, 1, 'FinTech for Unbanked Population', 'Create micro-lending, digital wallets, or BNPL services targeting 180M unbanked people. Attract foreign investment flows already at $1.9B in sector.', 'Pakistan-wide', '2026-03-07 06:37:31'),
(6, 1, 'Digital Marketing Agency for Startups', 'Offer social media, SEO, and content services to booming Pakistani startups (raised $74M+ recently). High demand as IT sector doubles every 2-4 years.', 'Islamabad / Online', '2026-03-07 06:37:31'),
(7, 1, 'Freelancing Platform or Training Hub', 'Launch skill-building programs or a niche freelancing agency (Pakistan top-5 global hub with $500M+ forex inflows). Focus on graphic design, writing, video editing.', 'Lahore / Online', '2026-03-07 06:37:31'),
(8, 1, 'Solar Energy Home Solutions Provider', 'Install affordable solar panels/chargers for homes & small businesses. Govt incentives and rising energy needs make this high-return.', 'Karachi / Rural Sindh', '2026-03-07 06:37:31'),
(9, 1, 'HealthTech App for Remote Consultations', 'Build telemedicine or wellness tracking app amid growing digital health adoption in Pakistan.', 'Pakistan-wide (Mobile)', '2026-03-07 06:37:31'),
(10, 1, 'Last-Mile Logistics Partnership', 'Start or join delivery service for e-commerce/small towns. Booming with Daraz & food apps expansion.', 'Multiple Cities', '2026-03-07 06:37:31'),
(11, 1, 'Online Education Platform (Skills/Tech)', 'Offer courses in coding, digital marketing, freelancing. Huge youth demand (67% under 30) and remote learning surge.', 'Online / Pakistan', '2026-03-07 06:37:31'),
(12, 1, 'Electric Vehicle Charging Network', 'Set up charging stations as EV promotion grows via govt policies. Partner with bike/car brands.', 'Urban Centers (Karachi/Lahore)', '2026-03-07 06:37:31'),
(13, 1, 'Content Creation & YouTube Automation Service', 'Help creators build faceless channels (scripting, editing, SEO). Low-cost, high-scaling with Pakistan’s creator economy.', 'Online', '2026-03-07 06:37:31'),
(14, 1, 'Micro-Retail Digitization Service', 'Help traditional shops go online with POS, inventory apps, and e-commerce integration (inspired by Bazaar model).', 'Small Towns Punjab/Sindh', '2026-03-07 06:37:31'),
(15, 1, 'Sustainable Fashion Export Brand', 'Start ethical clothing line (organic cotton, handmade) for local + international markets via textile strength.', 'Faisalabad / Karachi', '2026-03-07 06:37:31'),
(16, 1, 'Mobile Repair & Accessories Chain', 'Open shops or online store for phone repairs & accessories. Steady demand in youth-heavy market.', 'Karachi Markets', '2026-03-07 06:37:31'),
(17, 1, 'Online Grocery Delivery in Tier-2 Cities', 'Launch hyper-local delivery service focusing on fresh produce & essentials in smaller cities.', 'Rawalpindi / Multan', '2026-03-07 06:37:31'),
(18, 1, 'B2B SaaS for Small Retailers', 'Develop affordable tools for inventory, sales tracking, and payments tailored to Pakistani shops.', 'Lahore / Online', '2026-03-07 06:37:31'),
(19, 1, 'Women-Focused Micro-Entrepreneur Support', 'Provide training, micro-loans facilitation, and networking for female founders in underserved areas.', 'Pakistan-wide', '2026-03-07 06:37:31'),
(20, 1, 'Event Management for Corporate & Weddings', 'Affordable planning services for mid-range events, tapping into growing middle-class demand.', 'Islamabad / Lahore', '2026-03-07 06:37:31'),
(21, 1, 'Green Energy Consulting for SMEs', 'Advise small businesses on solar adoption, energy efficiency, and govt grants/subsidies.', 'Karachi Industrial Areas', '2026-03-07 06:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `tutorials`
--

CREATE TABLE `tutorials` (
  `tutorial_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorials`
--

INSERT INTO `tutorials` (`tutorial_id`, `title`, `description`, `video_url`, `created_at`) VALUES
(2, 'How to Validate Your Business Idea in 7 Days', 'Learn proven methods to test if your startup idea has real market demand before investing time and money — includes customer interviews and landing page tests.', 'https://www.youtube.com/watch?v=example-validation', '2026-03-07 06:18:12'),
(3, 'Writing a Winning Business Plan Step-by-Step', 'A complete guide to creating a professional business plan that investors and banks actually read — with free templates included.', 'https://www.youtube.com/watch?v=business-plan-guide', '2026-03-07 06:18:12'),
(4, 'Understanding Startup Costs and Budgeting', 'Break down hidden and obvious expenses every new entrepreneur faces and how to create a realistic 12-month budget.', 'https://www.youtube.com/watch?v=startup-budgeting', '2026-03-07 06:18:12'),
(5, 'Building Your Minimum Viable Product (MVP)', 'Step-by-step process to create and launch an MVP fast — real examples from successful startups.', 'https://www.youtube.com/watch?v=mvp-building', '2026-03-07 06:18:12'),
(6, 'Finding and Approaching Your First Customers', 'Strategies to get your first 10 paying customers through cold outreach, networking, and referrals.', 'https://www.youtube.com/watch?v=first-customers', '2026-03-07 06:18:12'),
(7, 'Social Media Marketing for New Businesses 2025', 'Best platforms, content types, and growth hacks for startups with zero ad budget in today’s environment.', 'https://www.youtube.com/watch?v=social-media-2025', '2026-03-07 06:18:12'),
(8, 'How to Pitch Investors & Raise Seed Funding', 'Craft a killer pitch deck, what investors really want to hear, and common mistakes to avoid.', 'https://www.youtube.com/watch?v=pitch-investors', '2026-03-07 06:18:12'),
(9, 'Pricing Your Product or Service Correctly', 'Psychology of pricing, competitor analysis, value-based vs cost-based pricing for maximum profit.', 'https://www.youtube.com/watch?v=pricing-strategy', '2026-03-07 06:18:12'),
(10, 'Bootstrapping vs Seeking Investors — Which is Better?', 'Pros, cons, and real stories of founders who chose each path and what worked best.', 'https://www.youtube.com/watch?v=bootstrap-vs-vc', '2026-03-07 06:18:12'),
(11, 'Building a Personal Brand as an Entrepreneur', 'How to use LinkedIn, YouTube, and Twitter/X to become known in your industry and attract opportunities.', 'https://www.youtube.com/watch?v=personal-branding', '2026-03-07 06:18:12'),
(12, 'Customer Feedback — How to Collect & Use It', 'Best tools and questions to ask so you can improve your product based on real user input.', 'https://www.youtube.com/watch?v=customer-feedback', '2026-03-07 06:18:12'),
(13, 'Hiring Your First Employee or Freelancer', 'Where to find talent, what to look for, simple contracts, and avoiding costly hiring mistakes.', 'https://www.youtube.com/watch?v=first-hire', '2026-03-07 06:18:12'),
(14, 'Growth Hacking Tactics That Still Work in 2025', 'Low-cost, high-impact experiments used by Dropbox, Airbnb, and modern startups.', 'https://www.youtube.com/watch?v=growth-hacking-2025', '2026-03-07 06:18:12'),
(15, 'From Side Hustle to Full-Time Business', 'Checklist and milestones to safely transition from part-time to full-time entrepreneurship.', 'https://www.youtube.com/watch?v=side-hustle-to-fulltime', '2026-03-07 06:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `cnic` varchar(13) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `cnic`, `created_at`) VALUES
(3, 'Nasir12', '$2y$10$UPVpRS26hxw8/GPw2kthrOFVJwzUu6Dpgzd/zeAkMbuKd5nXmb/M2', 'nasiryt.827@gmail.com', '03120558090', '32402-8443998', '2026-03-07 06:38:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`agent_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `business_ideas`
--
ALTER TABLE `business_ideas`
  ADD PRIMARY KEY (`idea_id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `business_templates`
--
ALTER TABLE `business_templates`
  ADD PRIMARY KEY (`template_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `mentorship_requests`
--
ALTER TABLE `mentorship_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`opportunity_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `tutorials`
--
ALTER TABLE `tutorials`
  ADD PRIMARY KEY (`tutorial_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `cnic` (`cnic`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `agent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_ideas`
--
ALTER TABLE `business_ideas`
  MODIFY `idea_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `business_templates`
--
ALTER TABLE `business_templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mentorship_requests`
--
ALTER TABLE `mentorship_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `opportunity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tutorials`
--
ALTER TABLE `tutorials`
  MODIFY `tutorial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `business_ideas`
--
ALTER TABLE `business_ideas`
  ADD CONSTRAINT `business_ideas_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`agent_id`),
  ADD CONSTRAINT `business_ideas_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `business_templates`
--
ALTER TABLE `business_templates`
  ADD CONSTRAINT `business_templates_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`agent_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`agent_id`);

--
-- Constraints for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `forum_replies_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`topic_id`),
  ADD CONSTRAINT `forum_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD CONSTRAINT `forum_topics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `mentorship_requests`
--
ALTER TABLE `mentorship_requests`
  ADD CONSTRAINT `mentorship_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `mentorship_requests_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`agent_id`);

--
-- Constraints for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD CONSTRAINT `opportunities_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`agent_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
