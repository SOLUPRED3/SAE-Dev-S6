import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ApiService } from '../services/api.service';
import { Livre } from '../models/livre';
import { Auteur } from '../models/auteur';
import { Categorie } from '../models/categorie';

@Component({
  selector: 'app-livre-detail',
  templateUrl: './livre-detail.component.html',
  styleUrls: ['./livre-detail.component.css']
})
export class LivreDetailComponent implements OnInit {
  livres: Livre[] = [];
  livre!: Livre ;
  auteurs: Auteur[] = [];
  categories: Categorie[] = [];

  constructor(
    private route: ActivatedRoute,
    private apiService: ApiService
  ) { }

  ngOnInit(): void {
    this.route.params.subscribe(params => {
      const titre = params['titre'];
      this.apiService.getLivres().subscribe((livres: Livre[]) => {
        this.livres = livres;
        this.livre = this.livres.find(l => l.titre === titre)!; // Definite assignment assertion
      });
      this.apiService.getAuteurs().subscribe((data: Auteur[]) => { 
        this.auteurs = data;
        console.log(this.categories);
      });
      this.apiService.getCategories().subscribe((data: Categorie[]) => {
        this.categories = data;
      });
    });
  }

  // Fonction pour obtenir le nom de l'auteur
  getAuteurName(auteurId: number): string {
    const auteur = this.auteurs.find(a => a.id === auteurId);
    return auteur ? `${auteur.nom} ${auteur.prenom}` : '';
  }

  //Fonction pour obtenir la catégorie
  getCategorieName(categorieId: number): string {
    const categorie = this.categories.find(c => c.id === categorieId);
    return categorie ? categorie.nom : '';
  }
}
