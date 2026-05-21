import { buildNatalChart } from '../utils/astro'

interface ReportRequest {
  firstName: string
  birthDate: string   // YYYY-MM-DD
  birthTime: string   // HH:mm
  lat: number
  lon: number
  city: string
  gender: string
  email?: string
}

export default defineEventHandler(async (event) => {
  const body = await readBody<ReportRequest>(event)

  // Validate input
  if (!body.birthDate || !body.lat || !body.lon) {
    throw createError({ statusCode: 400, statusMessage: 'Missing required fields' })
  }

  // Parse date + time
  const [year, month, day] = body.birthDate.split('-').map(Number)
  const [hours, minutes] = (body.birthTime || '12:00').split(':').map(Number)
  const hourDecimal = hours + minutes / 60

  // Calculate chart
  const chart = buildNatalChart(year, month, day, hourDecimal, body.lat, body.lon)

  // Generate AI summary if OpenAI key is configured
  const config = useRuntimeConfig()
  let summary = ''

  if (config.openaiApiKey) {
    try {
      summary = await generateAiSummary(body, chart, config.openaiApiKey as string)
    } catch (err) {
      console.error('[OpenAI] Error:', err)
      summary = generateFallbackSummary(body.firstName, chart)
    }
  } else {
    summary = generateFallbackSummary(body.firstName, chart)
  }

  return {
    firstName: body.firstName,
    birthDate: body.birthDate,
    city: body.city,
    sunSign: chart.sunSign,
    moonSign: chart.moonSign,
    ascendant: chart.ascendant,
    ascendantDegree: chart.ascendantDegree,
    planets: chart.planets,
    summary,
    fullAnalysis: null, // premium only
  }
})

async function generateAiSummary(
  user: ReportRequest,
  chart: ReturnType<typeof buildNatalChart>,
  apiKey: string,
): Promise<string> {
  const planetsList = chart.planets.map((p) => `${p.planet} en ${p.sign}`).join(', ')

  const prompt = `Tu es un expert en astrologie. Rédige un portrait astrologique personnalisé et bienveillant en français pour ${user.firstName}.

Données du thème natal :
- Signe Solaire : ${chart.sunSign}
- Signe Lunaire : ${chart.moonSign}
- Ascendant : ${chart.ascendant} (${chart.ascendantDegree}°)
- Planètes : ${planetsList}

Rédige un paragraphe de 150-200 mots qui synthétise la personnalité, les forces et les défis principaux. 
Style : direct, précis, encourageant. Évite les formules génériques. Commence par le prénom.`

  const res = await fetch('https://api.openai.com/v1/chat/completions', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${apiKey}`,
    },
    body: JSON.stringify({
      model: 'gpt-4o-mini',
      messages: [{ role: 'user', content: prompt }],
      max_tokens: 400,
      temperature: 0.8,
    }),
  })

  if (!res.ok) throw new Error(`OpenAI HTTP ${res.status}`)
  const data = await res.json() as { choices: Array<{ message: { content: string } }> }
  return data.choices[0]?.message?.content?.trim() || ''
}

function generateFallbackSummary(firstName: string, chart: ReturnType<typeof buildNatalChart>): string {
  return `${firstName}, votre thème natal révèle une personnalité façonnée par une combinaison unique d'énergies cosmiques. En tant que ${chart.sunSign}, vous portez en vous une énergie solaire distinctive. Votre Lune en ${chart.moonSign} guide votre vie émotionnelle intérieure, vous donnant une sensibilité particulière et des réactions instinctives caractéristiques. Votre Ascendant en ${chart.ascendant} constitue votre façade au monde — la première impression que vous laissez et la manière dont vous abordez les nouvelles situations. L'ensemble de vos positions planétaires dessine un portrait complexe et nuancé qui va bien au-delà de votre simple signe solaire. Chaque planète dans son signe apporte une couleur unique à votre personnalité, vos désirs, votre communication et votre façon d'interagir avec le monde. Votre rapport complet explore ces nuances en profondeur.`
}
