<?php

return [
    'price' => [
        'description' => 'The asking price for the business. This is one of the most important fields on your listing. It sets expectations for buyers immediately and determines which buyers your listing surfaces to. Enter a specific dollar figure, or use POA (Price on Application), or EOI (Expressions of Interest) if you are open to offers.', 
        'example' => '<strong>Example:</strong> $480,000 — or — POA — or — EOI',
        'tip' => 'If your price is negotiable, consider entering a figure and noting flexibility in your summary. Listings with a clear price typically attract more qualified enquiries.',
    ],
    'trading' => [
        'description' => 'The number of years the business has been operating under its current or previous ownership. This signals stability and track record to buyers. Include total years of operation, not just your time as owner.',
        'example' => '<strong>Example:</strong> 11 years',
        'tip' => 'If the business has changed hands, include the full operating history. A long trading history increases buyer confidence, particularly for first-time buyers.',
    ],
    'earning_type' => [
        'description' => 'The earnings metric that best represents the financial performance of the business. Common types include EBITDA, SDE (Seller’s Discretionary Earnings), or PEBITDA. If your earnings are structured differently or you are unsure, enter NA.',
        'example' => '<strong>Example:</strong> SDE — $210,000 per annum',
        'tip' => 'SDE is typically used for owner-operated businesses. EBITDA is more common for larger or investor-grade businesses. Using the right metric improves credibility with informed buyers.',
    ],
    'stock_level' => [
        'description' => 'The current value of inventory held by the business, including raw materials, finished goods, or consumables. Specify both the estimated value and a brief description of what the stock consists of. If the business holds no stock, enter Nil or Not Applicable.',
        'example' => '<strong>Example:</strong> $35,000 — consisting of branded uniforms, cleaning equipment, chemicals, and vehicle-mounted machinery.',
        'tip' => 'Buyers will want to know whether stock is included in the asking price or priced separately. If stock is excluded, note this clearly in your listing summary.',
    ],
    'summary' => [
        'description' => 'A concise overview of your business that leads with what the business does, what it sells or delivers, and what makes it a compelling opportunity. This is often the first thing a buyer reads. Focus on specifics such as industry, model, revenue drivers, and key strengths.',
        'example' => '<strong>Example:</strong> Established commercial cleaning and facilities management business with 11 years of operation. Serving over 80 recurring B2B clients across the industrial, healthcare, and education sectors. Strong referral network, trained staff in place, and fully systemised operations with documented SOPs. Owner is relocating interstate.',
        'tip' => 'Avoid starting with "We are a...". Lead with the opportunity. Buyers scan dozens of listings; your summary needs to earn the click.',
    ],
    'location_id' => [
        'description' => 'The suburb, city, or region where the business is based or primarily operates. Be specific enough to be useful. If the business operates nationally or remotely, state that clearly.',
        'example' => '<strong>Example:</strong> Dandenong South, VIC — Servicing clients across Greater Melbourne and regional Victoria.',
        'tip' => 'For service-area businesses, include operational coverage, not only office/depot location.',
    ],
    'skills' => [
        'description' => 'The qualifications, licences, certifications, or specialist knowledge required to operate the business or held by the current team.',
        'example' => '<strong>Example:</strong> Current owner holds a Cert IV in Occupational Health and Safety. Staff are trained in HACCP compliance, confined space entry, and working at heights. All vehicles are licensed and compliant.',
        'tip' => 'Be transparent about required skills. Buyers will verify this during due diligence.',
    ],
    'potential' => [
        'description' => 'Genuine, realistic growth opportunities that a new owner could pursue. Think about untapped markets, geographic expansion, product/service extensions, digital opportunities, or operational improvements.',
        'example' => '<strong>Example:</strong> The business currently operates Monday to Friday only. Introducing weekend servicing could add an estimated 20–25% to revenue. There is also strong demand from strata and commercial property managers that has not yet been pursued.',
        'tip' => 'Avoid exaggerated claims. Specific, credible growth pathways improve listing quality.',
    ],
    'hours' => [
        'description' => 'The regular trading or operating hours of the business. This helps buyers understand time commitment and lifestyle fit.',
        'example' => '<strong>Example:</strong> Monday to Friday, 6:00am to 4:00pm. Some early-morning client starts required. The owner currently works approximately 35 hours per week in an operational and supervisory capacity.',
        'tip' => 'If the business can run under management, mention it clearly as a key selling point.',
    ],
    'staff' => [
        'description' => 'The number of employees or contractors, their roles, and whether they are full-time, part-time, or casual.',
        'example' => '<strong>Example:</strong> 8 full-time employees: 1 operations manager, 6 field technicians, and 1 part-time administrator. All key staff have confirmed willingness to remain with a new owner.',
        'tip' => 'Disclose key-person dependencies honestly so buyer risk can be assessed early.',
    ],
    'lease' => [
        'description' => 'Details of the commercial lease for the business premises, including term, expiry, renewal options, monthly rental, and outgoings. If home-based or mobile, state this clearly.',
        'example' => '<strong>Example:</strong> Warehouse lease in Dandenong South. Current term expires June 2027 with a 3-year renewal option. Monthly rental of $4,200 plus outgoings.',
        'tip' => 'A short lease with no renewal can be a concern; secure options where possible.',
    ],
    'business_established' => [
        'description' => 'The year or date the business was originally founded.',
        'example' => '<strong>Example:</strong> Established 2013',
        'tip' => 'If rebranded/restructured, note both original establishment and major change dates.',
    ],
    'training' => [
        'description' => 'The handover support and training the current owner is prepared to offer post-settlement.',
        'example' => '<strong>Example:</strong> The seller will provide 4 weeks of full-time handover support, covering client introductions, staff management, supplier relationships, and operational systems.',
        'tip' => 'Specific and generous transition support lowers perceived buyer risk.',
    ],
    'awards' => [
        'description' => 'Industry awards, certifications, or formal recognition the business has received.',
        'example' => '<strong>Example:</strong> Winner of the 2022 and 2023 Master Cleaners Guild Excellence in Commercial Services award. ISO 9001 certified since 2019.',
        'tip' => 'If no awards exist, use strong testimonials or trusted third-party review proof points.',
    ],
    'reason_for_sale' => [
        'description' => 'The genuine reason the business is being sold. Buyers almost always ask this early in the process.',
        'example' => '<strong>Example:</strong> The owner is relocating to Queensland to be closer to family and is unable to continue managing operations from a distance. The business is in excellent health and the sale is not financially motivated.',
        'tip' => 'Be clear and direct. Vague answers often reduce buyer confidence.',
    ],

    'seeking_investment' => [
        'description' => 'The total amount of capital you are looking to raise in this round. Specify currency and whether this is total raise or a range.',
        'example' => '<strong>Example:</strong> $750,000 AUD — Seed round to fund product development, sales hiring, and 18 months of operating runway.',
        'tip' => 'If flexible, provide a minimum and maximum target range.',
    ],
    'reported_sales' => [
        'description' => 'Your most recent full-year audited or formally reported revenue figure.',
        'example' => '<strong>Example:</strong> $1.2M AUD — FY2024 (audited)',
        'tip' => 'If pre-revenue, state that explicitly rather than leaving this unclear.',
    ],
    'run_rate_sales' => [
        'description' => 'An annualised revenue figure based on your most recent trading period.',
        'example' => '<strong>Example:</strong> $2.1M ARR — Based on Q4 FY2024 monthly revenue of $175,000 x 12',
        'tip' => 'State how you calculated run-rate and disclose seasonality where relevant.',
    ],
    'ebitda_margin' => [
        'description' => 'Your Earnings Before Interest, Tax, Depreciation and Amortisation as a percentage of revenue.',
        'example' => '<strong>Example:</strong> 18% EBITDA margin on $1.2M reported revenue — equivalent to $216,000 EBITDA',
        'tip' => 'Keep margin assumptions realistic and tied to known cost drivers.',
    ],
    'industry' => [
        'description' => 'The primary industry or sector your business or project operates in.',
        'example' => '<strong>Example:</strong> Health Technology — B2B SaaS platform for allied health practice management',
        'tip' => 'Use the most specific industry label possible for better investor matching.',
    ],
    'assets_or_collateral' => [
        'description' => 'Tangible or intangible assets the business holds that could provide security for investors or lenders.',
        'example' => '<strong>Example:</strong> Assets include proprietary software platform valued at $380,000, $95,000 in accounts receivable, and server infrastructure with a replacement value of $60,000.',
        'tip' => 'For asset-light models, highlight IP, contracts, and data assets clearly.',
    ],
    'interested_to_connect_with_advisors' => [
        'description' => 'Select Yes if you are open to being introduced to advisors, brokers, or M&A specialists who may assist with your raise.',
        'example' => '<strong>Example:</strong> Yes — particularly interested in connecting with advisors experienced in SaaS capital raises and institutional investor introductions.',
        'tip' => 'Use this if you want help with structure, investor access, or deal process.',
    ],
    'business_overview' => [
        'description' => 'A comprehensive narrative of the business: what it does, who it serves, how it makes money, and why it exists.',
        'example' => '<strong>Example:</strong> Founded in 2021, NovaCare is a B2B SaaS platform connecting allied health practices with AI-driven scheduling, billing automation, and patient engagement tools. The platform serves over 340 active clinics across Australia and has grown 3.2x year-on-year.',
        'tip' => 'Write for a first-time reader with no context. Keep it clear, specific, and structured.',
    ],
    'products_and_services_overview' => [
        'description' => 'A breakdown of your products, services, or platform features, including who each is for and revenue contribution.',
        'example' => '<strong>Example:</strong> Core platform: three-tier SaaS subscription (Starter at $199/mo, Growth at $349/mo, Enterprise at $599/mo), with add-on onboarding and integration services.',
        'tip' => 'Clearly separate live offerings from roadmap items.',
    ],
    'assets_overview' => [
        'description' => 'A structured breakdown of key business assets, estimated values, and role in operations.',
        'example' => '<strong>Example:</strong> Proprietary codebase with $420,000 in capitalised development, de-identified dataset of 6.2M records, and $95,000 receivables within 30-day terms.',
        'tip' => 'Use line-item style detail so investors can quickly assess asset quality.',
    ],
    'facilities_overview' => [
        'description' => 'A description of physical premises, infrastructure, or operating facilities used by the business.',
        'example' => '<strong>Example:</strong> Hybrid remote model with leased co-working office in Sydney CBD and AWS hosting in Sydney/Singapore regions with 99.97% uptime SLA.',
        'tip' => 'Include infrastructure dependencies and recurring operating costs where relevant.',
    ],
    'capitalization_overview' => [
        'description' => 'A summary of current ownership and funding structure, including previous rounds and raise instrument.',
        'example' => '<strong>Example:</strong> Founder A 58%, Founder B 32%, angel investors 10%; this round seeks $750,000 via SAFE notes with a $6M valuation cap and 20% discount.',
        'tip' => 'Transparency here is critical. Keep ownership and instrument details accurate.',
    ],
];
