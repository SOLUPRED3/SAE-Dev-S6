import { Component, OnInit } from '@angular/core';
import { Livre } from '../models/livre';
import { ApiService } from '../services/api.service';
import { Auteur } from '../models/auteur';

@Component({
  selector: 'app-livres-list',
  templateUrl: './livres-list.component.html',
  styleUrl: './livres-list.component.css'
})
export class LivresListComponent implements OnInit {
  livres: Livre[] = [];
  auteurs: Auteur[] = [];
  constructor(private apiService: ApiService) {}

  ngOnInit(): void {
    this.apiService.getLivres().subscribe((data: Livre[]) => {
      this.livres = data;
      console.log(this.livres);
    });
    this.apiService.getAuteurs().subscribe((data: Auteur[]) => { 
      this.auteurs = data;
    });
  }

  getAuteurName(auteurId: number): string {
    const auteur = this.auteurs.find(a => a.id === auteurId); // Find author by ID
    return auteur ? `${auteur.nom} ${auteur.prenom}` : ''; // Return full name if found, otherwise return empty string
  }
}
