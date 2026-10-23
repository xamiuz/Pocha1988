# Anti-AI UI & Intentional Design Guidelines

## Mandatory Principle
All web interfaces, components, layouts, and styles in this project must adhere strictly to the Anti-AI-UI / Anti-AI-Slop design guidelines. Never generate homogenized "AI slop" or statistical-mean AI design patterns.

## Banned AI Slop Patterns (Zero Tolerance)
1. **No "Dot Dot" or Pulsing Circles**:
   - Never use `animate-pulse` or `animate-ping` on status dots.
   - Do not use standalone circular dot spans (`w-2 h-2 rounded-full`) inside badges or cards.
   - Do not use middle dots (`•`) as generic bullet separators. Use solid labels, pipe (`|`), comma (`,`), or clean SVG icons instead.
2. **No Eyebrow Floating Pills**:
   - Never place decorative floating pill badges directly above hero headings (`<h1>`).
3. **No Purple/Indigo Gradient Clichés**:
   - Avoid generic `indigo-500` / `purple-500` / `violet-500` and cyan/pink aurora gradients.
   - Use the project's authentic brand colors (1988 Pocha: Korean chili red `#e52e2e`, fresh emerald `#10b981`, warm charcoal, crisp white).
4. **No Cliché Layouts & Fake Copy**:
   - No PowerPoint-slide empty hero with centered text and dual symmetrical buttons.
   - No uniform 3-card grids with identical rounded squares and emojis.
   - No buzzwords ("Supercharge", "Seamless", "Next-Gen", "Unlock"). Use concrete, honest, human copy.
5. **No Glassmorphism Everywhere**:
   - Use clean, solid, high-contrast, accessible surfaces (`WCAG AA` compliant).

## Installed Skills Reference
Always consult the 3 installed skills in `.agents/skills/` and `.kiro/steering/`:
- **`anti-ai-slop`**: Exhaustive negative prompts against AI design clichés across all components.
- **`component-reference-design`**: Component-level reference architectures and design system guidelines.
- **`ui-ux-pro-max`**: Design intelligence, curated palettes, typography pairing, and visual hierarchy standards.
