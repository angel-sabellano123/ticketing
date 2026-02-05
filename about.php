<!DOCTYPE html>
<html>
<head>
    <title>About - SCC Ticketing System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* --- General Styling --- */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: gray;
        }

        header {
            background-color: gray;
            color: white;
            padding: 20px 40px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.8em;
        }

        /* Main content */
        .content {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 30px; /* Space between sections */
        }

        /* Each section as a card */
        .about-section {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            flex: 1 1 45%; /* 2 columns if screen is wide enough */
            min-width: 300px;
        }

        .about-section h2 {
            color: gray;
            margin-bottom: 15px;
        }

        .about-section p, .about-section ul {
            margin-bottom: 10px;
        }

        /* Core Values list style */
        ul.values {
            list-style-type: square;
            margin-left: 20px;
        }

        ul.values li {
            margin-bottom: 8px;
        }

        /* Buttons */
        .button {
            display: inline-block;
            padding: 10px 25px;
            margin: 20px 0;
            background-color: gray;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .button:hover {
            background-color: #0066cc;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 15px;
            background-color: gray;
            margin-top: 40px;
            font-size: 0.9em;
            color: black;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .about-section {
                flex: 1 1 100%; /* One column on small screens */
            }
        }
    </style>
</head>
<body>

<header>
    <h1>🎓 St. Cecilia’s College – Castillejos, Inc.</h1>
</header>

<main class="content">

    <div class="about-section">
        <h2>About St. Cecilia’s College</h2>
        <p>
            St. Cecilia’s College – Castillejos, Inc. is a Catholic educational institution
            committed to holistic formation rooted in Christian values, academic excellence,
            and service to the community.
        </p>
    </div>

    <div class="about-section">
        <h2>Cecilian Core Values</h2>
        <ul class="values">
            <li><strong>Christ-Centeredness</strong> – Cecilians put Christ at the center of their thoughts and actions.</li>
            <li><strong>Excellence</strong> – Cecilians strive to excel in imparting knowledge and skills.</li>
            <li><strong>Commitment</strong> – Cecilians go beyond what is required.</li>
            <li><strong>Integrity</strong> – Cecilians are honest and transparent in every thought, word, and deed.</li>
            <li><strong>Love for Country</strong> – Cecilians prioritize national interest and community service.</li>
            <li><strong>Innovativeness</strong> – Cecilians are open-minded and creative.</li>
            <li><strong>Arts Lover</strong> – Cecilians value and express creativity through the arts.</li>
            <li><strong>Nurturance</strong> – Cecilians show care for God’s creation and promote harmony.</li>
        </ul>
    </div>

    <div class="about-section">
        <h2>Mission</h2>
        <ul>
            <li>Cultivate Christian values to form men and women of faith and integrity</li>
            <li>Provide quality education in academics, technology, and the arts</li>
            <li>Foster love for country and service to fellowmen</li>
            <li>Upgrade teachers’ skills through continuous faculty development</li>
            <li>Develop critical thinking skills of students</li>
            <li>Provide opportunities to discover and enhance innate talents</li>
            <li>Promote love, care, and preservation of Mother Nature</li>
        </ul>
    </div>

    <div class="about-section">
        <h2>Vision</h2>
        <p>
            SCC-CI envisions itself as a Center of Excellence in Academics, Technology, and the Arts, 
            producing globally competitive professionals and leaders imbued with Christian values, 
            integrity, patriotism, and stewardship through quality education.
        </p>
    </div>

    <a href="homepage.php" class="button">Back</a>

</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> St. Cecilia’s College – Castillejos, Inc.</p>
</footer>

</body>
</html>
