<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" fill="none">
        <circle cx="60" cy="60" r="56" fill="#8B2E2E" />
        <circle cx="60" cy="60" r="44" fill="none" stroke="#F5F1E8" stroke-width="1.5" opacity="0.5" />
        <g fill="#F5F1E8">
            <g transform="translate(60 60)">
                <g id="grain">
                    <ellipse cx="0" cy="-38" rx="4" ry="9" />
                    <path d="M -3 -32 Q 0 -28 3 -32" stroke="#F5F1E8" stroke-width="1.2" fill="none" stroke-linecap="round" />
                </g>
                <use href="#grain" transform="rotate(45)" />
                <use href="#grain" transform="rotate(90)" />
                <use href="#grain" transform="rotate(135)" />
                <use href="#grain" transform="rotate(180)" />
                <use href="#grain" transform="rotate(225)" />
                <use href="#grain" transform="rotate(270)" />
                <use href="#grain" transform="rotate(315)" />
            </g>
        </g>
        <g transform="translate(60 60)">
            <circle r="18" fill="#D4A437" />
            <text x="0" y="7" text-anchor="middle" font-family="Fraunces, Georgia, serif" font-size="26" font-weight="600" fill="#5C3A1E">S</text>
        </g>
    </svg>
</template>
