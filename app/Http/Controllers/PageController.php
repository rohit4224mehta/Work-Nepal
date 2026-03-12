<?php
// app/Http/Controllers/PageController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\JobPosting;
use App\Models\Company;
use App\Models\Testimonial;
use App\Models\TeamMember;
use App\Models\FAQ;
use App\Models\User;
use App\Models\JobApplication;

class PageController extends Controller
{
    /**
     * About Us Page - Company story, mission, team, stats
     */
    public function about(): View
    {
        $stats = [
            'jobs' => JobPosting::where('status', 'active')->count(),
            'companies' => Company::where('verification_status', 'verified')->count(),
            'users' => User::where('account_status', 'active')->count(),
            'placements' => JobApplication::where('status', 'hired')->count(),
        ];

        $team = [
            [
                'name' => 'Rohit Mehta',
                'role' => 'Founder & CEO',
                'bio' => 'Passionate about connecting Nepali talent with opportunities',
                'image' => 'team/rohit.jpg',
                'linkedin' => 'https://linkedin.com/in/rohitmehta',
            ],
            // Add more team members
        ];

        $values = [
            [
                'title' => 'Trust & Verification',
                'description' => 'Every job posting is verified to ensure authenticity',
                'icon' => 'shield-check',
            ],
            [
                'title' => 'Empowering Nepali Youth',
                'description' => 'Creating opportunities for freshers and experienced professionals',
                'icon' => 'users',
            ],
            [
                'title' => 'Transparency',
                'description' => 'Clear communication between job seekers and employers',
                'icon' => 'eye',
            ],
            [
                'title' => 'Innovation',
                'description' => 'Using technology to simplify job search in Nepal',
                'icon' => 'light-bulb',
            ],
        ];

        $testimonials = Testimonial::where('is_approved', true)
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('pages.about', compact('stats', 'team', 'values', 'testimonials'));
    }

    /**
     * Contact Us Page - Form, map, contact details
     */
    public function contact(): View
    {
        $contactInfo = [
            'address' => 'Kathmandu, Nepal',
            'email' => 'support@worknepal.com',
            'phone' => '+977 1234567890',
            'hours' => 'Sun - Fri, 10:00 AM - 6:00 PM',
            'support' => 'help@worknepal.com',
            'careers' => 'careers@worknepal.com',
        ];

        $offices = [
            [
                'city' => 'Kathmandu',
                'address' => 'Putalisadak, Kathmandu 44600',
                'phone' => '+977 1 2345678',
                'map' => 'https://maps.google.com/?q=Kathmandu',
            ],
            [
                'city' => 'Pokhara',
                'address' => 'Lakeside, Pokhara 33700',
                'phone' => '+977 61 234567',
                'map' => 'https://maps.google.com/?q=Pokhara',
            ],
            [
                'city' => 'Biratnagar',
                'address' => 'Biratnagar 56613',
                'phone' => '+977 21 234567',
                'map' => 'https://maps.google.com/?q=Biratnagar',
            ],
        ];

        return view('pages.contact', compact('contactInfo', 'offices'));
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Send email to admin
        // Store in database if needed
        // Mail::to('admin@worknepal.com')->send(new ContactFormMail($validated));

        return back()->with('success', 'Thank you for contacting us. We will respond within 24 hours.');
    }

    /**
     * Privacy Policy Page - GDPR compliant, Nepal laws
     */
    public function privacy(): View
    {
        $lastUpdated = 'March 15, 2025';
        
        $sections = [
            [
                'title' => 'Information We Collect',
                'content' => 'We collect personal information you provide directly, including name, email, phone, resume, work history, education, and skills.',
                'subpoints' => [
                    'Account registration data',
                    'Profile information',
                    'Job applications and communications',
                    'Usage data and cookies',
                ]
            ],
            [
                'title' => 'How We Use Your Information',
                'content' => 'Your information helps us provide and improve our services.',
                'subpoints' => [
                    'Match you with relevant job opportunities',
                    'Process your job applications',
                    'Communicate with employers',
                    'Improve platform features',
                    'Ensure platform security',
                ]
            ],
            [
                'title' => 'Information Sharing',
                'content' => 'We share your information only as necessary for platform functionality.',
                'subpoints' => [
                    'Employers see your profile when you apply',
                    'Service providers help us operate',
                    'Legal compliance when required',
                ]
            ],
            [
                'title' => 'Your Rights',
                'content' => 'Under Nepal Privacy Law and GDPR standards, you have the right to:',
                'subpoints' => [
                    'Access your personal data',
                    'Correct inaccurate data',
                    'Delete your account',
                    'Export your data',
                    'Opt out of communications',
                ]
            ],
        ];

        return view('pages.privacy', compact('lastUpdated', 'sections'));
    }

    /**
     * Terms of Service Page
     */
    public function terms(): View
    {
        $lastUpdated = 'March 15, 2025';
        
        $terms = [
            [
                'title' => 'Account Terms',
                'content' => 'You must be 16 years or older to use this platform. You are responsible for maintaining account security.',
            ],
            [
                'title' => 'Job Seeker Responsibilities',
                'content' => 'You agree to provide accurate information and apply only to positions you are genuinely interested in.',
            ],
            [
                'title' => 'Employer Responsibilities',
                'content' => 'Employers must provide genuine job opportunities and respond to applications in a timely manner.',
            ],
            [
                'title' => 'Prohibited Activities',
                'points' => [
                    'Posting fraudulent job listings',
                    'Misrepresenting company information',
                    'Harassing other users',
                    'Scraping platform data',
                    'Posting illegal content',
                ]
            ],
            [
                'title' => 'Content Ownership',
                'content' => 'You retain ownership of your content but grant us license to display it on the platform.',
            ],
            [
                'title' => 'Termination',
                'content' => 'We reserve the right to suspend or terminate accounts that violate these terms.',
            ],
        ];

        return view('pages.terms', compact('lastUpdated', 'terms'));
    }

    /**
     * CV Tips & Career Advice Page
     */
    public function cvTips(): View
    {
        $tips = [
            [
                'title' => 'CV Structure That Works',
                'icon' => 'document-text',
                'points' => [
                    'Use reverse chronological order',
                    'Keep it to 2 pages maximum',
                    'Include relevant keywords',
                    'Use professional fonts (Arial, Calibri)',
                ]
            ],
            [
                'title' => 'Key Sections to Include',
                'icon' => 'clipboard-list',
                'sections' => [
                    ['name' => 'Contact Information', 'tip' => 'Email, phone, location, LinkedIn'],
                    ['name' => 'Professional Summary', 'tip' => '3-4 sentences highlighting your expertise'],
                    ['name' => 'Work Experience', 'tip' => 'Focus on achievements, not just duties'],
                    ['name' => 'Education', 'tip' => 'Include relevant certifications'],
                    ['name' => 'Skills', 'tip' => 'List technical and soft skills'],
                ]
            ],
            [
                'title' => 'Nepal-Specific Tips',
                'icon' => 'map-pin',
                'points' => [
                    'Include both English and Nepali languages if relevant',
                    'Mention local certifications',
                    'Add location preferences clearly',
                    'Include references from previous employers',
                ]
            ],
            [
                'title' => 'Common Mistakes to Avoid',
                'icon' => 'exclamation-circle',
                'mistakes' => [
                    'Spelling and grammar errors',
                    'Generic CV for all jobs',
                    'Missing contact information',
                    'Too much personal information',
                    'Unprofessional email address',
                ]
            ],
        ];

        $templates = [
            ['name' => 'Professional Template', 'preview' => 'cv-templates/professional.jpg', 'download' => '#'],
            ['name' => 'Creative Template', 'preview' => 'cv-templates/creative.jpg', 'download' => '#'],
            ['name' => 'Simple Template', 'preview' => 'cv-templates/simple.jpg', 'download' => '#'],
            ['name' => 'Technical Template', 'preview' => 'cv-templates/technical.jpg', 'download' => '#'],
        ];

        $articles = [
            [
                'title' => 'How to Write a CV for Nepal Job Market',
                'excerpt' => 'Learn what Nepali employers look for in a CV...',
                'url' => '#',
                'date' => '2025-03-01',
            ],
            [
                'title' => 'Top 10 Skills Employers Want in 2025',
                'excerpt' => 'Discover the most in-demand skills in Nepal...',
                'url' => '#',
                'date' => '2025-02-15',
            ],
            [
                'title' => 'Ace Your Job Interview: Tips for Freshers',
                'excerpt' => 'Prepare for your first job interview with confidence...',
                'url' => '#',
                'date' => '2025-02-01',
            ],
        ];

        return view('pages.cv-tips', compact('tips', 'templates', 'articles'));
    }

    /**
     * Foreign Employment Safety Guide
     */
    public function foreignSafety(): View
    {
        $warnings = [
            [
                'title' => 'Red Flags to Watch',
                'icon' => 'exclamation-triangle',
                'points' => [
                    'Too good to be true salary offers',
                    'Requests for upfront payment',
                    'Unprofessional communication',
                    'No proper company documentation',
                    'Pressure to decide quickly',
                ]
            ],
            [
                'title' => 'Verify Before You Apply',
                'icon' => 'check-circle',
                'steps' => [
                    'Check company registration',
                    'Verify with Nepal Department of Foreign Employment',
                    'Research company online',
                    'Talk to previous employees',
                    'Get offer letter reviewed',
                ]
            ],
            [
                'title' => 'Required Documents',
                'icon' => 'document-duplicate',
                'documents' => [
                    'Valid passport',
                    'Employment contract in Nepali',
                    'Work visa',
                    'Insurance details',
                    'Company registration proof',
                ]
            ],
            [
                'title' => 'Safe Countries & Guidelines',
                'icon' => 'globe-alt',
                'countries' => [
                    'UAE - Strict labor laws, minimum wage',
                    'Qatar - Worker protection laws',
                    'Malaysia - Standard contracts',
                    'Saudi Arabia - Wage protection system',
                ]
            ],
        ];

        $resources = [
            [
                'name' => 'Department of Foreign Employment',
                'url' => 'https://www.dofe.gov.np',
                'phone' => '01-2440700',
                'services' => 'License verification, complaint registration',
            ],
            [
                'name' => 'Nepal Embassy - UAE',
                'url' => 'https://uae.nepalembassy.gov.np',
                'phone' => '+971 2 1234567',
                'services' => 'Emergency assistance, document attestation',
            ],
            [
                'name' => 'Ministry of Labour',
                'url' => 'https://www.moless.gov.np',
                'phone' => '01-1234567',
                'services' => 'Labor rights, policy information',
            ],
        ];

        $faqs = [
            [
                'question' => 'What should I check before accepting a foreign job?',
                'answer' => 'Verify company registration, ensure proper contract, check salary meets minimum wage, confirm visa type, and research country labor laws.',
            ],
            [
                'question' => 'How can I verify a recruitment agency?',
                'answer' => 'Check with Department of Foreign Employment for valid license, read reviews, and never pay fees before getting proper documentation.',
            ],
            [
                'question' => 'What are my rights as a foreign worker?',
                'answer' => 'You have right to fair wages, safe working conditions, proper accommodation, medical insurance, and freedom to contact embassy.',
            ],
            [
                'question' => 'What documents should I keep with me?',
                'answer' => 'Passport, work visa, employment contract (in Nepali), emergency contacts, and embassy address.',
            ],
        ];

        $recentIncidents = [
            [
                'country' => 'UAE',
                'warning' => 'Beware of fake construction jobs in Dubai',
                'date' => '2025-03-10',
            ],
            [
                'country' => 'Qatar',
                'warning' => 'Verify security guard job offers',
                'date' => '2025-03-05',
            ],
            [
                'country' => 'Malaysia',
                'warning' => 'Check manufacturing company credentials',
                'date' => '2025-02-28',
            ],
        ];

        return view('pages.foreign-safety', compact('warnings', 'resources', 'faqs', 'recentIncidents'));
    }

    /**
     * Help Center Page - Comprehensive support articles and FAQs
     */
    public function helpCenter(Request $request): View
    {
        $searchQuery = $request->input('search');
        $searchResults = []; // Initialize empty array for search results
        
        // Popular help articles by category
        $helpCategories = [
            'getting-started' => [
                'title' => 'Getting Started',
                'icon' => 'rocket',
                'articles' => [
                    [
                        'question' => 'How do I create an account?',
                        'answer' => 'Click "Sign Up" on the top right. You can register using email, mobile number, or Google account. Email verification or OTP will be sent for confirmation.',
                        'views' => 15420,
                        'helpful' => 98
                    ],
                    [
                        'question' => 'Is WorkNepal free to use?',
                        'answer' => 'Yes! WorkNepal is completely free for job seekers. You can create profiles, search jobs, apply, and track applications at no cost. Employers have both free and paid options.',
                        'views' => 12350,
                        'helpful' => 96
                    ],
                    [
                        'question' => 'How do I complete my profile?',
                        'answer' => 'Go to Dashboard → Edit Profile. Complete personal info, professional headline, work experience, education, skills, and upload your CV and photo for better visibility.',
                        'views' => 9870,
                        'helpful' => 94
                    ],
                    [
                        'question' => 'What is profile completion score?',
                        'answer' => 'Your profile completion score shows how complete your profile is. Higher scores (80%+) get better job recommendations and appear more in employer searches.',
                        'views' => 7650,
                        'helpful' => 91
                    ],
                ]
            ],
            'account-profile' => [
                'title' => 'Account & Profile',
                'icon' => 'user',
                'articles' => [
                    [
                        'question' => 'How do I change my password?',
                        'answer' => 'Go to Settings → Security → Change Password. You\'ll need your current password and enter a new one (minimum 8 characters with mix of letters and numbers).',
                        'views' => 8760,
                        'helpful' => 95
                    ],
                    [
                        'question' => 'How do I update my email or phone?',
                        'answer' => 'In Settings → Account, you can update your email or phone. Verification will be required for the new contact information.',
                        'views' => 6540,
                        'helpful' => 93
                    ],
                    [
                        'question' => 'How do I delete my account?',
                        'answer' => 'Go to Settings → Account → Delete Account. Confirm with your password. This action is permanent and removes all your data from our platform.',
                        'views' => 5430,
                        'helpful' => 88
                    ],
                    [
                        'question' => 'How do I update my CV?',
                        'answer' => 'Go to Profile → CV & Documents. You can upload a new CV (PDF/DOC, max 5MB), replace existing, or delete current CV.',
                        'views' => 10980,
                        'helpful' => 97
                    ],
                ]
            ],
            'job-search' => [
                'title' => 'Finding Jobs',
                'icon' => 'search',
                'articles' => [
                    [
                        'question' => 'How do I search for jobs?',
                        'answer' => 'Use the search bar on homepage or go to Jobs section. Filter by location, category, job type, salary range, and experience level for precise results.',
                        'views' => 23450,
                        'helpful' => 99
                    ],
                    [
                        'question' => 'How do I apply for a job?',
                        'answer' => 'On job details page, click "Apply Now". You can use your profile CV, upload a new one, or add a cover letter. You\'ll receive confirmation email after applying.',
                        'views' => 18760,
                        'helpful' => 98
                    ],
                    [
                        'question' => 'How do I track my applications?',
                        'answer' => 'Go to Dashboard → My Applications. Track status: Applied → Under Review → Shortlisted → Rejected → Hired. You\'ll get notifications on status changes.',
                        'views' => 15670,
                        'helpful' => 96
                    ],
                    [
                        'question' => 'Can I save jobs to apply later?',
                        'answer' => 'Yes! Click the bookmark icon on any job listing. Saved jobs appear in Dashboard → Saved Jobs for easy access.',
                        'views' => 12430,
                        'helpful' => 95
                    ],
                ]
            ],
            'applications' => [
                'title' => 'Applications',
                'icon' => 'document-text',
                'articles' => [
                    [
                        'question' => 'What do application statuses mean?',
                        'answer' => 'Applied: Application sent. Viewed: Employer saw it. Shortlisted: Selected for next round. Rejected: Not selected. Hired: Got the job.',
                        'views' => 14320,
                        'helpful' => 94
                    ],
                    [
                        'question' => 'Can I withdraw an application?',
                        'answer' => 'Yes, go to My Applications, find the job, and click "Withdraw Application". This action cannot be undone.',
                        'views' => 4320,
                        'helpful' => 87
                    ],
                    [
                        'question' => 'How long until employers respond?',
                        'answer' => 'Response times vary. Most employers respond within 1-2 weeks. You can follow up once if no response after 2 weeks.',
                        'views' => 8760,
                        'helpful' => 89
                    ],
                    [
                        'question' => 'Why was my application rejected?',
                        'answer' => 'Common reasons: qualifications mismatch, experience level, or position filled. Some employers provide feedback in application status.',
                        'views' => 6540,
                        'helpful' => 82
                    ],
                ]
            ],
            'employers' => [
                'title' => 'For Employers',
                'icon' => 'briefcase',
                'articles' => [
                    [
                        'question' => 'How do I post a job?',
                        'answer' => 'After creating company profile, go to Employer Dashboard → Post New Job. Fill details, set requirements, and submit for review. Jobs are verified within 24 hours.',
                        'views' => 8760,
                        'helpful' => 96
                    ],
                    [
                        'question' => 'How do I manage applications?',
                        'answer' => 'In Employer Dashboard, view all applicants, download CVs, update application status, and message candidates directly through platform.',
                        'views' => 6540,
                        'helpful' => 94
                    ],
                    [
                        'question' => 'How do I verify my company?',
                        'answer' => 'Provide company registration documents, official email, and complete company profile. Verified companies get "Verified" badge.',
                        'views' => 5430,
                        'helpful' => 97
                    ],
                    [
                        'question' => 'What are the pricing plans?',
                        'answer' => 'Free: 1 job posting. Premium: Unlimited jobs, featured listings, analytics. Enterprise: Custom solutions. Check Pricing page for details.',
                        'views' => 4320,
                        'helpful' => 92
                    ],
                ]
            ],
            'foreign-jobs' => [
                'title' => 'Foreign Jobs',
                'icon' => 'globe-alt',
                'articles' => [
                    [
                        'question' => 'How do I find genuine foreign jobs?',
                        'answer' => 'Look for "Verified" badge, check company details, verify with Department of Foreign Employment, and read our Foreign Safety Guide.',
                        'views' => 10980,
                        'helpful' => 98
                    ],
                    [
                        'question' => 'What documents do I need?',
                        'answer' => 'Valid passport, employment contract (in Nepali), work visa, insurance, and company registration proof. Never pay without proper documentation.',
                        'views' => 8760,
                        'helpful' => 96
                    ],
                    [
                        'question' => 'How to verify recruitment agencies?',
                        'answer' => 'Check license with Department of Foreign Employment, read reviews, verify physical office, and never pay fees before contract signing.',
                        'views' => 7650,
                        'helpful' => 97
                    ],
                    [
                        'question' => 'What are my rights abroad?',
                        'answer' => 'Right to fair wages, safe conditions, proper accommodation, medical insurance, and embassy contact. Keep embassy address and emergency numbers handy.',
                        'views' => 6540,
                        'helpful' => 95
                    ],
                ]
            ],
            'technical' => [
                'title' => 'Technical Support',
                'icon' => 'cog',
                'articles' => [
                    [
                        'question' => 'Why am I not receiving emails?',
                        'answer' => 'Check spam folder, verify email in settings, whitelist @worknepal.com, or update your email if bouncing.',
                        'views' => 5430,
                        'helpful' => 88
                    ],
                    [
                        'question' => 'Why can\'t I upload my CV?',
                        'answer' => 'Check file format (PDF/DOC only), size (max 5MB), and filename (no special characters). Try renaming file or converting format.',
                        'views' => 4320,
                        'helpful' => 86
                    ],
                    [
                        'question' => 'How do I enable notifications?',
                        'answer' => 'In Settings → Notifications, toggle email and browser notifications for job alerts, application updates, and messages.',
                        'views' => 3210,
                        'helpful' => 91
                    ],
                    [
                        'question' => 'Is my data secure?',
                        'answer' => 'Yes, we use encryption, secure servers, and follow Nepal privacy laws. Never share passwords. Enable 2FA for extra security.',
                        'views' => 2100,
                        'helpful' => 99
                    ],
                ]
            ],
        ];

        // Featured articles (most viewed/helpful)
        $featuredArticles = [
            [
                'title' => 'Complete Guide to Job Search on WorkNepal',
                'excerpt' => 'Learn how to find and apply for jobs effectively on our platform',
                'url' => '#',
                'category' => 'Getting Started',
                'views' => 45230,
            ],
            [
                'title' => 'How to Create a Standout Profile',
                'excerpt' => 'Tips to make your profile attractive to employers',
                'url' => '#',
                'category' => 'Profile Tips',
                'views' => 38920,
            ],
            [
                'title' => 'Understanding Application Statuses',
                'excerpt' => 'What each status means and what to do next',
                'url' => '#',
                'category' => 'Applications',
                'views' => 34150,
            ],
            [
                'title' => 'Safety Guide for Foreign Jobs',
                'excerpt' => 'Essential tips to avoid scams when applying abroad',
                'url' => '#',
                'category' => 'Foreign Jobs',
                'views' => 29870,
            ],
        ];

        // Search functionality
        if ($searchQuery) {
            $searchResults = [];
            $searchTerms = explode(' ', strtolower($searchQuery));
            
            foreach ($helpCategories as $categoryKey => $category) {
                foreach ($category['articles'] as $article) {
                    $matchScore = 0;
                    $articleText = strtolower($article['question'] . ' ' . $article['answer']);
                    
                    foreach ($searchTerms as $term) {
                        if (strlen($term) > 2) { // Ignore very short terms
                            if (strpos($articleText, $term) !== false) {
                                $matchScore += substr_count($articleText, $term);
                            }
                        }
                    }
                    
                    if ($matchScore > 0) {
                        $searchResults[] = [
                            'category' => $category['title'],
                            'category_key' => $categoryKey,
                            'question' => $article['question'],
                            'answer' => $article['answer'],
                            'match_score' => $matchScore,
                            'views' => $article['views'],
                        ];
                    }
                }
            }
            
            // Sort by match score
            usort($searchResults, function($a, $b) {
                return $b['match_score'] - $a['match_score'];
            });
            
            $searchResults = array_slice($searchResults, 0, 10); // Top 10 results
        }

        // Quick stats
        $helpStats = [
            'total_articles' => array_sum(array_map(function($cat) {
                return count($cat['articles']);
            }, $helpCategories)),
            'helpful_votes' => 15420,
            'avg_response_time' => '2.5 hours',
            'satisfaction_rate' => 96,
        ];

        // Popular search terms
        $popularSearches = [
            'create account', 'apply job', 'upload cv', 
            'forgot password', 'foreign job', 'delete account'
        ];

        return view('pages.help-center', compact(
            'helpCategories',
            'featuredArticles',
            'searchQuery',
            'searchResults', // Now always initialized as empty array
            'helpStats',
            'popularSearches'
        ));
    }

    /**
     * Get single help article details (AJAX endpoint)
     */
    public function getHelpArticle(Request $request, $category, $index)
    {
        $helpCategories = [
            'getting-started' => [
                'articles' => [
                    ['question' => 'How do I create an account?', 'answer' => 'Click "Sign Up" on the top right. You can register using email, mobile number, or Google account. Email verification or OTP will be sent for confirmation.'],
                    ['question' => 'Is WorkNepal free to use?', 'answer' => 'Yes! WorkNepal is completely free for job seekers.'],
                    ['question' => 'How do I complete my profile?', 'answer' => 'Go to Dashboard → Edit Profile.'],
                    ['question' => 'What is profile completion score?', 'answer' => 'Your profile completion score shows how complete your profile is.'],
                ]
            ],
            'account-profile' => [
                'articles' => [
                    ['question' => 'How do I change my password?', 'answer' => 'Go to Settings → Security → Change Password.'],
                    ['question' => 'How do I update my email or phone?', 'answer' => 'In Settings → Account, you can update your email or phone.'],
                    ['question' => 'How do I delete my account?', 'answer' => 'Go to Settings → Account → Delete Account.'],
                    ['question' => 'How do I update my CV?', 'answer' => 'Go to Profile → CV & Documents.'],
                ]
            ],
            'job-search' => [
                'articles' => [
                    ['question' => 'How do I search for jobs?', 'answer' => 'Use the search bar on homepage or go to Jobs section.'],
                    ['question' => 'How do I apply for a job?', 'answer' => 'On job details page, click "Apply Now".'],
                    ['question' => 'How do I track my applications?', 'answer' => 'Go to Dashboard → My Applications.'],
                    ['question' => 'Can I save jobs to apply later?', 'answer' => 'Yes! Click the bookmark icon on any job listing.'],
                ]
            ],
            'applications' => [
                'articles' => [
                    ['question' => 'What do application statuses mean?', 'answer' => 'Applied: Application sent. Viewed: Employer saw it. Shortlisted: Selected for next round.'],
                    ['question' => 'Can I withdraw an application?', 'answer' => 'Yes, go to My Applications, find the job, and click "Withdraw Application".'],
                    ['question' => 'How long until employers respond?', 'answer' => 'Response times vary. Most employers respond within 1-2 weeks.'],
                    ['question' => 'Why was my application rejected?', 'answer' => 'Common reasons: qualifications mismatch, experience level, or position filled.'],
                ]
            ],
            'employers' => [
                'articles' => [
                    ['question' => 'How do I post a job?', 'answer' => 'After creating company profile, go to Employer Dashboard → Post New Job.'],
                    ['question' => 'How do I manage applications?', 'answer' => 'In Employer Dashboard, view all applicants, download CVs, update application status.'],
                    ['question' => 'How do I verify my company?', 'answer' => 'Provide company registration documents, official email, and complete company profile.'],
                    ['question' => 'What are the pricing plans?', 'answer' => 'Free: 1 job posting. Premium: Unlimited jobs, featured listings, analytics.'],
                ]
            ],
            'foreign-jobs' => [
                'articles' => [
                    ['question' => 'How do I find genuine foreign jobs?', 'answer' => 'Look for "Verified" badge, check company details, verify with Department of Foreign Employment.'],
                    ['question' => 'What documents do I need?', 'answer' => 'Valid passport, employment contract (in Nepali), work visa, insurance, and company registration proof.'],
                    ['question' => 'How to verify recruitment agencies?', 'answer' => 'Check license with Department of Foreign Employment, read reviews, verify physical office.'],
                    ['question' => 'What are my rights abroad?', 'answer' => 'Right to fair wages, safe conditions, proper accommodation, medical insurance, and embassy contact.'],
                ]
            ],
            'technical' => [
                'articles' => [
                    ['question' => 'Why am I not receiving emails?', 'answer' => 'Check spam folder, verify email in settings, whitelist @worknepal.com.'],
                    ['question' => 'Why can\'t I upload my CV?', 'answer' => 'Check file format (PDF/DOC only), size (max 5MB), and filename (no special characters).'],
                    ['question' => 'How do I enable notifications?', 'answer' => 'In Settings → Notifications, toggle email and browser notifications.'],
                    ['question' => 'Is my data secure?', 'answer' => 'Yes, we use encryption, secure servers, and follow Nepal privacy laws.'],
                ]
            ],
        ];

        if (isset($helpCategories[$category]) && isset($helpCategories[$category]['articles'][$index])) {
            return response()->json([
                'success' => true,
                'article' => $helpCategories[$category]['articles'][$index]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Article not found'], 404);
    }

    /**
     * Submit feedback on help article (helpful/not helpful)
     */
    public function submitHelpfulFeedback(Request $request)
    {
        $request->validate([
            'article_id' => 'required|string',
            'helpful' => 'required|boolean',
        ]);

        // Store feedback in database or session
        // For now, just return success

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback!'
        ]);
    }

    /**
     * Contact support from help center
     */
    public function helpContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // Handle attachment if present
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('help-attachments', 'public');
            $validated['attachment_path'] = $path;
        }

        // Send to support team
        // Mail::to('support@worknepal.com')->send(new HelpRequestMail($validated));

        return back()->with('success', 'Your support request has been sent. We\'ll respond within 24 hours.');
    }
}