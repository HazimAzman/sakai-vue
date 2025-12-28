// Brand → Product lines configuration (extend as needed)
export const brands = {
  hettich: {
    name: 'Hettich Lab Technology',
    lines: [
      {
        slug: 'eba200-200s',
        name: 'EBA 200-200S',
        summary: 'Hettich Lab Technology centrifuge accessory for liquid handling workflows.',
        brochureUrl: '/catalog/HETTICH/EBA200-200S_EN (1).pdf',
        images: [
          // Place exported brochure images here (ensure files exist in /public)
          '/images/productpage/hettich/eba200/1.png',
          '/images/productpage/hettich/eba200/2.png'
        ],
        highlights: [
          'Compact centrifuge accessory for reliable workflows',
          'Designed for consistent performance and everyday lab use',
          'See brochure for detailed specs and configurations'
        ]
      },
      // Add more Hettich Lab Technology lines here...
    ]
  },
  thermofisher: {
    name: 'ThermoFisher Scientific',
    lines: [
      {
        slug: 'genesys150',
        name: 'Genesys 150',
        summary: 'ThermoFisher Scientific Spectrophotometer for accurate measurements.',
        brochureUrl: '/catalog/THERMO/Genesys150.pdf',
        images: [
          '/images/productpage/thermo/Genesys/1.png'
        ],
        highlights: [
          'High-performance spectrophotometer for laboratory use',
          'Designed for accurate and reliable measurements',
          'See brochure for detailed specifications and features'
        ]
      },
      // Add more ThermoFisher Scientific lines here...
    ]
  },
  raxvision: {
    name: 'RAXVISION',
    lines: [
      {
        slug: 'bi400',
        name: 'BI400',
        summary: 'RAXVISION Biological Microscope for inspection and analysis.',
        brochureUrl: '/catalog/RAXVISION/bi400.pdf',
        images: [
          '/images/productpage/raxvision/bi400/1.png'
        ],
        highlights: [
          'High-quality biological microscope for laboratory use',
          'Designed for precise inspection and analysis',
          'See brochure for detailed specifications and features'
        ]
      },
      // Add more RAXVISION lines here...
    ]
  }
};

export function normalizeBrand(slugOrName = '') {
  return String(slugOrName).toLowerCase().replace(/\s+/g, '-');
}

export function findBrand(slugOrName) {
  let key = normalizeBrand(slugOrName);
  
  // Brand name mappings (handle legacy names)
  const brandMappings = {
    'baxvision': 'raxvision'
  };
  if (brandMappings[key]) {
    key = brandMappings[key];
  }
  
  if (brands[key]) return { key, ...brands[key] };
  // Try by display name
  const foundKey = Object.keys(brands).find(k => normalizeBrand(brands[k].name) === key);
  return foundKey ? { key: foundKey, ...brands[foundKey] } : null;
}

export function findLine(brandKey, lineSlug) {
  const brand = brands[brandKey];
  if (!brand) return null;
  const key = normalizeBrand(lineSlug);
  return brand.lines.find(l => normalizeBrand(l.slug || l.name) === key) || null;
}


