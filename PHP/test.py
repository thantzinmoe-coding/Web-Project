import pygame
import numpy as np
import sounddevice as sd
import random

# Initialize Pygame
pygame.init()

# Screen setup
WIDTH, HEIGHT = 800, 500
screen = pygame.display.set_mode((WIDTH, HEIGHT))
pygame.display.set_caption("Fourier Piano Game")

# Colors
WHITE = (255, 255, 255)
BLACK = (0, 0, 0)
GRAY = (50, 50, 50)
BLUE = (100, 149, 237)
GREEN = (144, 238, 144)
RED = (255, 69, 0)

# Fonts
font_large = pygame.font.Font(None, 48)
font_small = pygame.font.Font(None, 24)

# Piano key setup
WHITE_KEYS = [pygame.Rect(x * 100, 150, 100, 200) for x in range(8)]
BLACK_KEYS = [pygame.Rect(x * 100 + 75, 150, 50, 120) for x in range(7) if x != 2 and x != 6]

# Notes and their frequencies (C4 to C5)
NOTES = ['C', 'D', 'E', 'F', 'G', 'A', 'B', 'C5']
FREQUENCIES = [261.63, 293.66, 329.63, 349.23, 392.00, 440.00, 493.88, 523.25]

# Game state
sequence = []
current_index = 0
playing_note = False
score = 0
wrong_note = False

# Sound synthesis function
def play_note_using_fourier(frequency, duration=0.4, sample_rate=44100, num_harmonics=5):
    t = np.linspace(0, duration, int(sample_rate * duration), False)
    wave = np.zeros_like(t)
    
    for n in range(1, num_harmonics + 1):
        wave += (1 / n) * np.sin(2 * np.pi * frequency * n * t)
    
    wave = 0.5 * wave / np.max(np.abs(wave))
    sd.play(wave, samplerate=sample_rate)
    sd.wait()

# Draw piano keys
def draw_piano():
    for i, key in enumerate(WHITE_KEYS):
        pygame.draw.rect(screen, WHITE, key, border_radius=10)
        pygame.draw.rect(screen, BLACK, key, 2)
        text_surface = font_small.render(NOTES[i], True, BLACK)
        screen.blit(text_surface, (key.x + 40, key.y + 170))

    for key in BLACK_KEYS:
        pygame.draw.rect(screen, BLACK, key, border_radius=5)

# Show win screen
def show_win_screen():
    screen.fill(GRAY)
    text_surface = font_large.render(f"You Won! Score: {score}", True, GREEN)
    screen.blit(text_surface, (WIDTH // 2 - text_surface.get_width() // 2, HEIGHT // 2 - 20))
    pygame.display.flip()
    pygame.time.delay(2000)

# Show wrong note message
def show_wrong_note():
    text_surface = font_large.render("Wrong Note! Try Again.", True, RED)
    screen.blit(text_surface, (WIDTH // 2 - text_surface.get_width() // 2, HEIGHT - 50))

# Level selection
def select_level():
    global sequence
    screen.fill(GRAY)
    easy_button = pygame.Rect(150, 300, 150, 50)
    normal_button = pygame.Rect(325, 300, 150, 50)
    hard_button = pygame.Rect(500, 300, 150, 50)

    while True:
        screen.fill(GRAY)
        pygame.draw.rect(screen, BLUE, easy_button, border_radius=10)
        pygame.draw.rect(screen, BLUE, normal_button, border_radius=10)
        pygame.draw.rect(screen, BLUE, hard_button, border_radius=10)
        
        screen.blit(font_small.render("Easy", True, WHITE), (200, 315))
        screen.blit(font_small.render("Normal", True, WHITE), (375, 315))
        screen.blit(font_small.render("Hard", True, WHITE), (550, 315))
        pygame.display.flip()
        
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                pygame.quit()
                exit()
            if event.type == pygame.MOUSEBUTTONDOWN:
                if easy_button.collidepoint(event.pos):
                    sequence = [random.randint(0, 7) for _ in range(4)]
                    return
                if normal_button.collidepoint(event.pos):
                    sequence = [random.randint(0, 7) for _ in range(6)]
                    return
                if hard_button.collidepoint(event.pos):
                    sequence = [random.randint(0, 7) for _ in range(8)]
                    return

# Play sequence
def play_sequence():
    global playing_note
    playing_note = True
    print("Sequence to play:", [NOTES[note_index] for note_index in sequence])  # Print sequence in terminal
    for note_index in sequence:
        play_note_using_fourier(FREQUENCIES[note_index])
        pygame.time.delay(500)
    playing_note = False

# Game loop
select_level()
play_sequence()
running = True
while running:
    screen.fill(GRAY)
    draw_piano()
    if wrong_note:
        show_wrong_note()
    
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            running = False

        if event.type == pygame.MOUSEBUTTONDOWN:
            for i, key in enumerate(WHITE_KEYS):
                if key.collidepoint(event.pos):
                    play_note_using_fourier(FREQUENCIES[i])
                    if i == sequence[current_index]:
                        current_index += 1
                        if current_index == len(sequence):
                            score += 1
                            show_win_screen()
                            select_level()
                            play_sequence()
                            current_index = 0
                            wrong_note = False
                    else:
                        current_index = 0
                        wrong_note = True

    pygame.display.flip()

pygame.quit()
