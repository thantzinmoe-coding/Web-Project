import pygame
import numpy as np
import sounddevice as sd
import random

# Initialize Pygame
pygame.init()

# Screen setup
WIDTH, HEIGHT = 800, 400
screen = pygame.display.set_mode((WIDTH, HEIGHT))
pygame.display.set_caption("Fourier Piano Game")

# Colors
WHITE = (255, 255, 255)
BLACK = (0, 0, 0)
GRAY = (200, 200, 200)

# Piano key setup
WHITE_KEYS = [pygame.Rect(x * 100, 100, 100, 200) for x in range(8)]
BLACK_KEYS = [pygame.Rect(x * 100 + 75, 100, 50, 100) for x in range(7) if x != 2 and x != 6]

# Notes and their frequencies (C4 to C5)
NOTES = ['C', 'D', 'E', 'F', 'G', 'A', 'B', 'C5']
FREQUENCIES = [261.63, 293.66, 329.63, 349.23, 392.00, 440.00, 493.88, 523.25]

# Game state
sequence = [random.randint(0, 7) for _ in range(5)]  # Random sequence of notes
current_index = 0
playing_note = False

# Sound synthesis function
def play_note(frequency, duration=0.5, sample_rate=44100):
    t = np.linspace(0, duration, int(sample_rate * duration), False)
    wave = 0.5 * np.sin(2 * np.pi * frequency * t)  # Generate sine wave
    sd.play(wave, samplerate=sample_rate)
    sd.wait()

# Display sequence to the player
def play_sequence():
    global playing_note
    playing_note = True
    for note_index in sequence:
        play_note(FREQUENCIES[note_index])
        pygame.time.delay(500)
    playing_note = False

# Draw piano keys
def draw_piano():
    for key in WHITE_KEYS:
        pygame.draw.rect(screen, WHITE, key)
        pygame.draw.rect(screen, BLACK, key, 2)

    for key in BLACK_KEYS:
        pygame.draw.rect(screen, BLACK, key)

# Game loop
running = True
show_sequence = True

while running:
    screen.fill(GRAY)
    draw_piano()

    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            running = False

        if event.type == pygame.MOUSEBUTTONDOWN and not playing_note:
            # Check which key was clicked
            for i, key in enumerate(WHITE_KEYS):
                if key.collidepoint(event.pos):
                    play_note(FREQUENCIES[i])  # Play the note
                    if i == sequence[current_index]:
                        current_index += 1
                        if current_index == len(sequence):
                            print("You completed the sequence!")
                            sequence = [random.randint(0, 7) for _ in range(5)]  # New sequence
                            current_index = 0
                            show_sequence = True
                    else:
                        print("Wrong note! Try again.")
                        current_index = 0
                        show_sequence = True

    # Show sequence to the player
    if show_sequence:
        play_sequence()
        show_sequence = False

    pygame.display.flip()

pygame.quit()