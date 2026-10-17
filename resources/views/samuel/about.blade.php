@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl p-8 md:p-10 shadow-xl">
        <div class="max-w-2xl mx-auto text-center mb-12">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#D4A72C] to-[#F59E0B] mx-auto mb-6 flex items-center justify-center">
                <svg class="w-8 h-8 text-[#172554]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M17 9V5a2 2 0 0-1 2-2h1a2 2 0 0 1 2 2v4m0 4V5a2 2 0 0-1 2-2h1a2 2 0 0 1 2 2v4m-6-6h6m5-12l3 8l7 2l-3 8l-7-2L21 7l-7-2z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-[#172554] mb-4">Samuel App</h2>
            <p class="text-lg text-[#6B7280] mb-6">Version 1.0</p>
        </div>

        <div class="space-y-6">
            <!-- Mission -->
            <div>
                <h3 class="text-xl font-semibold text-[#172554] mb-4">Notre mission</h3>
                <p class="text-base text-[#4A5568] leading-relaxed">
                    Samuel App a été créé pour moderniser l'organisation et le suivi des activités des Samuels (servants d'autel) au sein de l'Église catholique. Notre objectif est de fournir une plateforme numérique intuitive qui facilite la formation, la consultation des cours et le suivi des présences.
                </p>
            </div>

            <!-- Vision -->
            <div>
                <h3 class="text-xl font-semibold text-[#172554] mb-4">Notre vision</h3>
                <p class="text-base text-[#4A5568] leading-relaxed">
                    Devenir la référence numérique pour la formation des servants d'autel, en rendant les ressources liturgiques accessibles à tout moment et en simplifiant la gestion administrative au service de l'Église.
                </p>
            </div>

            <!-- Objectifs -->
            <div>
                <h3 class="text-xl font-semibold text-[#172554] mb-4">Nos objectifs</h3>
                <ul class="list-disc list-inside text-[#4A5568] space-y-2">
                    <li>Centraliser les cours et documents de formation des Samuels</li>
                    <li>Faciliter l'apprentissage des fonctions liturgiques</li>
                    <li>Permettre la consultation des cours même sans connexion Internet</li>
                    <li>Simplifier l'enregistrement et le suivi des présences</li>
                    <li>Moderniser l'organisation des activités ecclésiales</li>
                    <li>Sécuriser les comptes et contrôler les autorisations selon les rôles</li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-xl font-semibold text-[#172554] mb-4">Contact</h3>
                <p class="text-base text-[#6B7280]">
                    Pour toute demande d'information, veuillez contacter l'administrateur de la plateforme.
                </p>
            </div>
        </div>
    </div>
</div>